<template>
  <div class="client-group-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="clientGroupFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="save">
            <div class="modal-header">
              <button
                type="button"
                class="btn-close"
                aria-label="Close"
                id="close-button"
                data-dismiss="modal"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.Name")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.name.$model"
                      :class="{
                        'is-invalid': v$.name.$error || nameExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name.$errors" :key="error">
                        {{ $t("global.Name") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.name.$invalid && nameExist">
                        {{ $t("global.Exist", { field: $t("global.Name") }) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="multi-select mb-2">
                    <label>{{ $t("global.Clients") }}</label>
                    <div
                      :class="{
                        'is-invalid':
                          clientsTouched && getSelectedClientsIds().length == 0,
                      }"
                      class="select border p-2"
                    >
                      <div
                        v-for="(client, index) in clients"
                        :key="client.id"
                        class="form-check"
                      >
                        <input
                          class="form-check-input"
                          type="checkbox"
                          @change="toggleClientSelection(client)"
                          :id="index"
                          :checked="client.selected"
                        />
                        <label class="form-check-label" for="flexCheckChecked">
                          {{ client.user.name }}
                        </label>
                      </div>
                    </div>
                    <div class="invalid-feedback">
                      {{ $t("global.Clients") + " " + $t("required") }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">
                {{ $t("global.Submit") }}
              </button>
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                {{ $t("global.Close") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import clientGroupClient from "../../../http-clients/client-group-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const client_group_store = inject("client_group_store");
    const data = reactive({
      nameExist: false,
      clientsTouched: false,
    });
    const form = reactive({
      id: null,
      name: "",
    });
    const rules = {
      name: { required },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function toggleClientSelection(client) {
      client.selected = !client.selected;
      data.clientsTouched = true;
    }

    function getSelectedClientsIds() {
      return props.clients
        .filter((client) => {
          return client.selected;
        })
        .map((client) => client.id);
    }

    function save() {
      if (v$.value.$invalid || getSelectedClientsIds().length == 0) {
        v$.value.$touch();
        data.clientsTouched = true;
        return;
      }
      if (!props.selectedClientGroup) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function setSelectedClients() {
      if (props.selectedClientGroup) {
        props.clients.forEach((client) => {
          client.selected = props.selectedClientGroup.clients
            .map((_client) => (_client.id ? _client.id : _client))
            .includes(client.id);
        });
      } else {
        props.clients.forEach((client) => (client.selected = false));
      }
    }
    function alertMessage(message) {
      notify({
        title: `${t(message)} <i class="fas fa-check-circle"></i>`,
        type: "success",
        duration: 5000,
        speed: 2000,
      });
    }
    function store() {
      context.emit("loading", true);
      data.nameExist = false;
      clientGroupClient
        .store(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("created", response.data);
          alertMessage("global.AddedSuccessfully");
          $("#close-button").click();
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
          context.emit("loading", false);
        });
    }
    function update() {
      context.emit("loading", true);
      data.nameExist = false;
      clientGroupClient
        .update(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("updated", response.data);
          $("#close-button").click();
          alertMessage("global.EditSuccessfully");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
          context.emit("loading", false);
        });
    }
    function getForm() {
      return {
        id: props.selectedClientGroup ? props.selectedClientGroup.id : null,
        name: form.name,
        clients_ids: getSelectedClientsIds(),
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedClientGroup
        ? props.selectedClientGroup.name
        : "";
      setSelectedClients();
      data.clientsTouched = false;
      data.nameExist = false;
    }
    //Watchers
    watch(
      () => {
        client_group_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      toggleClientSelection,
      getSelectedClientsIds,
      v$,
      locale,
      save,
    };
  },
  props: ["selectedClientGroup", "clients"],
};
</script>

<style scoped lang="scss">
.client-group-form {
  .select {
    border-radius: 0.25rem;
    height: 150px;
    overflow: auto;
  }
  .form-control {
    padding: 10px;
  }
  .form-group {
    margin-bottom: 10px;
    label {
      margin-bottom: 5px;
    }
  }
  .modal-footer {
    button {
      width: 80px;
    }
  }
}
</style>