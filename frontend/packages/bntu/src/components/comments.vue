<template>
  <div>
    <template v-if="fetching"> Загрузка... </template>
    <template v-if="data">
      <el-card class="box-card mt-2 mb-2" v-for="comment of data.comment">
        <template #header>
          <div class="clearfix pl-2">
            <el-button
              class="button"
              type="text"
              style="font-size: 14px; border: none"
            >
              Анонимный автор
            </el-button>
            <el-button
              class="button"
              v-if="comment.by_current_user"
              @click="archiveComment(comment.id)"
              style="
                float: right;
                padding-left: 10px;
                padding-right: 10px;
                border: none;
              "
              icon="el-icon-close"
              type="text"
            >
            </el-button>
            <el-button
              class="button"
              :title="comment.created_at"
              style="float: right; padding: 0; font-size: 14px; border: none"
              type="text"
              >{{ formatDate(comment.created_at) }}
            </el-button>
          </div>
        </template>
        <p class="pr-2 comment-text" v-html="linkify(comment.text)"></p>
        <template
          v-for="link of comment.attachments.filter((i) => i.type === 'link')"
        >
          <a :href="link.value">
            {{ link.name ? link.name : link.value }}
          </a>
          &nbsp;
        </template>
        <template
          v-if="
            comment.attachments &&
            comment.attachments.filter((i) => i.type !== 'link').length > 0
          "
        >
          <hr style="margin-top: 5px; margin-bottom: 5px" />
          <span
            class="pr-3"
            v-for="(attachment, index) of comment.attachments.filter(
              (i) => i.type !== 'link'
            )"
          >
            <a :href="attachment.url" target="_blank" style="font-size: 13px">
              Прикрепленный файл {{ index + 1 }}
            </a>
          </span>
        </template>
      </el-card>
      <a @click="showAll = true" v-if="!showAll" class="mt-3">
        Показать все комментарии({{ data.comments.length }})...
      </a>
    </template>
    <form @submit.prevent="save" class="pt-3">
      <div class="field is-grouped">
        <p class="control is-expanded">
          <el-input
            type="textarea"
            :placeholder="''"
            resize="vertical"
            rows="3"
            v-model="form.text"
            maxlength="380"
            show-word-limit
          >
          </el-input>
        </p>
      </div>
      <div class="buttons-comments-bntu">
        <div class="control">
          <el-upload
            class="upload-demo"
            :auto-upload="false"
            ref="upload"
            action="https://jsonplaceholder.typicode.com/posts/"
            :on-change="onChange"
            :on-remove="handleRemove"
            multiple
            :limit="3"
            accept="image/*,video/*,audio/*,application/pdf"
            :on-exceed="handleExceed"
            :file-list="fileList"
          >
            <button
              class="bntu-button bntu-button-main bntu-button-main-article"
              ref="upload_btn"
              type="button"
            >
              Прикрепить файл(ы)
            </button>
          </el-upload>
        </div>

        <button
          class="bntu-button bntu-button-empty bntu-button-empty-article"
          :class="{ 'is-loading': isLoading }"
          :disabled="form.text.length === 0"
          type="submit"
        >
          Отправить
        </button>
      </div>
    </form>
  </div>
  <toast></toast>
</template>

<script>
import {
  ElButton,
  ElCard,
  ElUpload,
  ElMessage,
  ElIcon,
  ElInput,
} from 'element-plus';
import linkifyHtml from 'linkifyjs/html';
import {useMutation, useQuery} from '@urql/vue';
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import { formatDate } from '../date';

const emptyComment = {
  text: '',
  attachments: [],
};

export default {
  components: {
    ElCard,
    ElButton,
    ElUpload,
    ElIcon,
    ElInput,
    Toast,
  },
  props: {
    type: String,
    id: Number,
  },
  setup(props) {
    const toast = useToast();
    const variables = {
      where: {
        [props.type]: {
          id: { _eq: props.id },
        },
      },
    };
    const archiveComment = useMutation(// language=GraphQL
        `
mutation($id: Int!, $date: timestamp) {
    update_comment_by_pk(pk_columns: {id: $id}, _set: {hidden_at: $date}) {
        id
    }
}`
    );
    const result = useQuery({
      // language=GraphQL
      query: `
query($where: comment_bool_exp) {
    comment(where: $where, order_by: [{created_at: desc}]) {
        id
        text
        by_current_user
        attachments
        created_at
    }
}
      `,
      variables,
    });
    const form = ref({
      text: '',
      attachments: [],
    });
    const isLoading = ref(false);
    const refresh = () => {
      result.executeQuery({
        requestPolicy: 'network-only',
      });
    };
    const upload = ref(null);
    return {
      formatDate,
      fetching: result.fetching,
      data: result.data,
      error: result.error,
      map: null,
      fileList: [],
      showAll: true,
      dialogVisible: false,
      isLoading,
      upload,
      form,
      linkify(text) {
        return linkifyHtml(text);
      },
      handleExceed(files, fileList) {
        ElMessage.error('Максимум три файла!');
      },
      save() {
        const formData = new FormData();
        formData.append('text', form.value.text);
        formData.append('type', props.type);
        formData.append('id', props.id + '');

        form.value.attachments.forEach((elem, index) => {
          formData.append('attachment' + index, elem.raw);
        });
        isLoading.value = true;
        fetch( '/comment', {
          method: 'POST',
          body: formData,
          credentials: 'include',
        })
          .then((r) => r.json())
          .then((r) => {
            isLoading.value = false;
            if (r.error) {
              ElMessage.error(r.error);
              return;
            }
            refresh();
            Object.assign(form.value, emptyComment);
            upload.value.clearFiles();
          })
          .catch((e) => {
            isLoading.value = false;
            ElMessage.error('Произошла ошибка');
            throw e;
          });
      },
      handleRemove(file) {
        form.value.attachments = form.value.attachments.filter(
          (i) => file.uid !== i.uid
        );
      },
      onChange(file) {
        form.value.attachments.push(file);
      },
      archiveComment(id) {
        archiveComment.executeMutation({
            id,
            date: (new Date()).toISOString()
        }).catch((e) => {
            toast.add({
                severity: 'error',
                summary: 'Произошла ошибка',
                life: 3000,
            });
            throw e;
        }).then((payload) => {
            if (payload.error) {
                toast.add({
                    severity: 'error',
                    summary: 'Произошла ошибка',
                    life: 3000,
                });
                throw payload.error;
            }
            refresh();
        })
      },
    };
  },
  computed: {
    comments() {
      return this.showAll ? this.list : this.list.slice(0, 2);
    },
  },
};
</script>

<style>
.el-card__header {
  padding: 0 10px !important;
}

.comment-text {
  word-wrap: break-word;
  white-space: pre-wrap;
  font-size: 14px;
}

.clearfix:before,
.clearfix:after {
  display: table;
  content: '';
}

.clearfix:after {
  clear: both;
}

.buttons-comments-bntu {
  display: flex;
  align-items: center;
}
@media screen and (max-width: 480px) {
  .buttons-comments-bntu {
    flex-direction: column;
    align-items: flex-start;
  }
  .control {
    width: 100%;
  }
  .upload-demo {
    width: 100%;
  }
  .el-upload--text {
    width: 100% !important;
  }
}
</style>
