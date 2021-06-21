<template>
    <button class="button is-success is-small"
            :title="this.entity.upvotes"
            :class="{'is-outlined' : !entity.is_upvoted}"
            @click="upvote">
        <i class="fa fa-arrow-up"></i>
    </button>
    <span class="pl-3 pr-3 pt-3">
        {{ rating }}
    </span>
    <button class="button is-danger is-small"
            :title="this.entity.downvotes"
            :class="{'is-outlined' : !entity.is_downvoted}"
            @click="downvote">
        <i class="fa fa-arrow-down"></i>
    </button>
</template>
<script>
import handle from './../http'

export default {
    props   : {
        entity: Object,
        type  : String,
        id    : Number
    },
    emits   : ['change'],
    computed: {
        rating() {
            return this.entity.upvotes - this.entity.downvotes;
        },
    },
    methods : {
        upvote() {
            if (this.entity.is_upvoted) {
                this.unrate();
                return;
            }
            fetch(import.meta.env.VITE_TELEGRAM_API_URL
                + '/rate/upvote/'
                + this.type
                + '/'
                + this.$route.params.id,
                {
                    'method'     : 'POST',
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
                    'method'     : 'POST',
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
                    'method'     : 'POST',
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
    }
}
</script>
