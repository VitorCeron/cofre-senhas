const emailPattern = /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;

export const email = val => emailPattern.test(val) || 'Digite um e-mail válido';
export const required = val => !!val || 'O campo é obrigatório';
export const lengthMoreThan6 = val => val.length >= 6 || 'A senha precisa ter pelo menos 6 caracteres';