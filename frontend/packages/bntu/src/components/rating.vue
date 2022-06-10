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
        // language=GraphQL
        const upvote = useMutation(`
mutation upvote_person($id: Int!) {
  insert: insert_rating_point_one(
      object: {person_id: $id, type: "upvote"},
      on_conflict: {constraint: rating_per_person, update_columns: [type]}
  ) {
        person {
          rating {
            upvotes
            downvotes
            rating: overall
          }
        }
  }
}
    `);
        // language=GraphQL
        const downvote = useMutation(`
mutation upvote_person($id: Int!) {
  insert: insert_rating_point_one(
    object: {person_id: $id, type: "downvote"},
    on_conflict: {constraint: rating_per_person, update_columns: [type]}
    ) {
        person {
          rating {
            upvotes
            downvotes
            rating: overall
          }
        }
  }
}
    `);
        // language=GraphQL
        const unrate = useMutation(`
mutation unrate_person($id: Int!) {
  delete_rating_point(where: {person_id: {_eq: $id}}) {
    affected_rows
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
