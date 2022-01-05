<template>
  <button class="button is-success is-small"
          :title="modelValue !== null ? modelValue.upvotes : 0"
          :class="{'is-outlined' : !(modelValue !== null && modelValue.is_upvoted)}"
          @click="upvote">
    <i class="fa fa-arrow-up"></i>
  </button>
  <span class="pl-3 pr-3 pt-3">
    {{ modelValue !== null ? modelValue.upvotes - modelValue.downvotes : 0 }}
  </span>
  <button class="button is-danger is-small"
          :title="modelValue !== null ? modelValue.downvotes : 0"
          :class="{'is-outlined' : !(modelValue !== null && modelValue.is_downvoted)}"
          @click="downvote">
    <i class="fa fa-arrow-down"></i>
  </button>
  <toast></toast>
</template>
<script>
import {useMutation} from "@urql/vue";
import {useToast} from "primevue/usetoast";
import Toast from "primevue/toast";

export default {
  props: {
    id: Number,
    modelValue: Object
  },
  components: {
    Toast
  },
  setup(props, context) {
    const toast = useToast();
    const upvote = useMutation(`
      mutation ($id: Int!) {
        person_upvote(person_id: $id) {
          downvotes
          upvotes
          rating
        }
      }
    `);
    const downvote = useMutation(`
      mutation ($id: Int!) {
        person_downvote(person_id: $id) {
          downvotes
          upvotes
          rating
        }
      }
    `);
    const unrate = useMutation(`
      mutation ($id: Int!) {
        person_unrate(person_id: $id) {
          downvotes
          upvotes
          rating
        }
      }
    `);
    const handler = (result) => {
      if (result.error) {
        let message = 'Произошла ошибка'
        if (result.error.message === '[GraphQL] not_authorized') {
          message = 'Вы не авторизованы'
        }
        toast.add({severity: 'error', summary: message, life: 3000});
        return;
      }
      context.emit("change")
    }
    return {
      upvote() {
        if (props.modelValue && props.modelValue.is_upvoted) {
          unrate.executeMutation({id: props.id}).then(handler);
        } else {
          upvote.executeMutation({id: props.id}).then(handler);
        }
      },
      downvote() {
        if (props.modelValue && props.modelValue.is_downvoted) {
          unrate.executeMutation({id: props.id}).then(handler);
        } else {
          downvote.executeMutation({id: props.id}).then(handler);
        }
      },
    }
  },
  emits: ['change']
}
</script>
