<template>
  <button class="button is-success is-small"
          :title="modelValue !== null ? modelValue.upvotes : 0"
          :class="{'is-outlined' : false}"
          @click="upvote">
    <i class="fa fa-arrow-up"></i>
  </button>
  <span class="pl-3 pr-3 pt-3">
    {{ modelValue !== null ? modelValue.upvotes - modelValue.downvotes : 0 }}
  </span>
  <button class="button is-danger is-small"
          :title="modelValue !== null ? modelValue.downvotes : 0"
          :class="{'is-outlined' : false}"
          @click="downvote">
    <i class="fa fa-arrow-down"></i>
  </button>
</template>
<script>
import {useMutation} from "@urql/vue";

export default {
  props: {
    id: Number,
    modelValue: Object
  },
  setup(props, context) {
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
    return {
      upvote() {
        upvote.executeMutation({id: props.id}).then(result => {
          console.log(result)
          context.emit("change")
        });
      },
      downvote() {
        downvote.executeMutation({id: props.id}).then(result => {
          context.emit("change")
          console.log(result)
        });
      }
    }
  },
  emits: ['change'],
  /*methods: {
    unrate() {
      fetch(import.meta.env.VITE_TELEGRAM_API_URL
          + '/unrate/'
          + this.type
          + '/'
          + this.$route.params.id,
          {
            'method': 'POST',
            'credentials': 'include',
          }
      )
          .then(handle)
          .then(
              () => {
                this.$emit('change');
              }
          )
    },
  }*/
}
</script>
