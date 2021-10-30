<template>
  <button class="button is-success is-small"
          :title="upvotes"
          :class="{'is-outlined' : false}"
          @click="upvote">
    <i class="fa fa-arrow-up"></i>
  </button>
  <span class="pl-3 pr-3 pt-3">
        {{ rating }}
  </span>
  <button class="button is-danger is-small"
          :title="downvotes"
          :class="{'is-outlined' : false}"
          @click="downvote">
    <i class="fa fa-arrow-down"></i>
  </button>
</template>
<script>
import {useMutation} from "@urql/vue";
import {computed, ref} from "vue";

export default {
  props: {
    id: Number,
    stats: Object
  },
  setup(props) {
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
    const upvotes = ref(props.stats.upvotes);
    const downvotes = ref(props.stats.downvotes);
    const rating = computed(() => {
      return upvotes.value - downvotes.value;
    })
    return {
      upvotes,
      downvotes,
      rating,
      upvote() {
        upvote.executeMutation({id: props.id}).then(result => {
          console.log(result)
        });
      },
      downvote() {
        downvote.executeMutation({id: props.id}).then(result => {
          console.log(result)
        });
      }
    }
  },
  emits: ['change'],
  /*methods: {
    downvote() {
      if (this.entity.is_downvoted) {
        this.unrate();
        return;
      }
      fetch(import.meta.env.VITE_TELEGRAM_API_URL
          + '/rate/downvote/'
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
