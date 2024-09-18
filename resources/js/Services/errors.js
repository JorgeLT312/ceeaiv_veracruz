import { useNotification } from "@kyvg/vue3-notification";

const { notify }  = useNotification();

function showErrors(data) {

    if (data.errors != undefined) {
        let tempErrors = Object.keys(data.errors);
        if (tempErrors.length) {
            let message = '';
            tempErrors.forEach((item) => message += ('<p>- '+data.errors[item]+'</p>'))

            notify({
                title: "¡Error!",
                text: message,
                type: "error"
            });
        }
    } else if (data.response) {
        notify({
                title: "¡Error "+data.response.status+"!",
                text: data.response.statusText,
                type: "error"
            });
    } else if (data.message) {
        notify({
                title: data.code ? data.code : "¡Error!",
                text: data.message,
                type: "error"
            });
    }
}

export default function useErrors() {
  return {
    showErrors
  };
}
