import { Notify } from "quasar";

export function toast ({ type, message }) {
    Notify.create({
        type,
        message,
        position: 'top-right',
        progress: true,
        timeout: 3000
    });
}