import { extend } from 'vee-validate';
import { required, email, min , max, image,confirmed , numeric } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'El campo {_field_} es requerido'
});

extend('email', {
  ...email,
  message: 'El campo {_field_} debe ser de un correo electronico valido'
});

extend('min', {
  ...min,
  message: 'El campo {_field_} debe tener un minimo de {length} caracteres'
});

extend('max', {
  ...max,
  message: 'El campo {_field_} debe tener un maximo de {length} caracteres'
});

extend('image', {
  ...image,
  message: 'Seleccione una archivo de imagen valido'
});

extend('confirmed', {
  ...confirmed,
  message: 'El campo debe ser igual al otro'
});

extend('numeric', {
  ...numeric,
  message: 'El campo {_field_} debe ser de tipo numerico'
});

extend('url', {
  validate: value =>{
    const expresion = /^(ftp|http|https):\/\/[^ "]+$/gm;
    const regex = new RegExp(expresion)
    return regex.test(value)
  },
  message: 'El campo {_field_} debe ser una URL valida'
});