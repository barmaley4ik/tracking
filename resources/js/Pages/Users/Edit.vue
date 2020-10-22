<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="mb-8 font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" href="/admin/users">Пользователи</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>Редактирование
      </h1>
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">
      Этот пользователь был удален
    </trashed-message>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model.trim="form.first_name" :errors="$page.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Имя" />
          <text-input v-model.trim="form.last_name" :errors="$page.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Фамилия" />
          <text-input v-model.trim="form.email" :errors="$page.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email" />
          <text-input v-model.trim="form.password" :errors="$page.errors.password" class="pr-6 pb-8 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Пароль" />
          <select-input v-if="$page.auth.user.owner" v-model="form.owner" :errors="$page.errors.owner" class="pr-6 pb-8 w-full lg:w-1/2" label="Администратор">
            <option :value="true">Да</option>
            <option :value="false">Нет</option>
          </select-input>
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
          <button v-if="!user.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Удалить</button>
          <loading-button :loading="sending" class="btn-indigo ml-auto" type="submit">Обновить</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  metaInfo() {
    return {
      title: `${this.form.first_name} ${this.form.last_name}`,
    }
  },
  layout: Layout,
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  props: {
    user: Object,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email: this.user.email,
        password: this.user.password,
        owner: this.user.owner,

      },
    }
  },
  methods: {
    submit() {
      this.sending = true

      var data = new FormData()
      data.append('first_name', this.form.first_name || '')
      data.append('last_name', this.form.last_name || '')
      data.append('email', this.form.email || '')
      data.append('password', this.form.password || '')
      data.append('owner', this.form.owner ? '1' : '0')
      data.append('_method', 'put')

      this.$inertia.post('/admin/users/' + this.user.id, data)
        .then(() => {
          this.sending = false
          if (Object.keys(this.$page.errors).length === 0) {
            this.form.password = null
          }
        })
    },
    destroy() {
      this.$swal({
        title: 'Вы действительно хотите удалить данного пользователя?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Подтвердить',
        cancelButtonText: 'Отмена',
        showCloseButton: true,
        showLoaderOnConfirm: false,
        animation: false,
      }).then((result) => {
        if(result.value) {
          this.$inertia.delete('/admin/users/'+ this.user.id)
        }
      })
    },
    restore() {
      this.$swal({
        title: 'Вы действительно хотите восстановить данного пользователя?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Подтвердить',
        cancelButtonText: 'Отмена',
        showCloseButton: true,
        showLoaderOnConfirm: false,
        animation: false,
      }).then((result) => {
        if(result.value) {
          this.$inertia.put(this.route('users.restore', this.user.id))
        }
      })
    },
  },
}

</script>
<style>
.swal2-title {
  font-size: 1.4em;
  }
</style>
