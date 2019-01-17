import store from '@/js/stores'

export default async (to, from, next) => {
  if (store.getters['auth/check']) {
    let user = store.getters['auth/user'];
    next({ name: 'home'});
  } else {
    next();
  }
}
