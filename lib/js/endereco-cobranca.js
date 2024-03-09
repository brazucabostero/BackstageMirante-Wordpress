jQuery('.e-form__buttons__wrapper__button-next').on('click', () => {
  let userLogged = JSON.parse(Cookies.get('wp_ing_user'));  
  let imgElement = jQuery('#ings-user-avatar');
  imgElement.attr('src', userLogged.photo);
  console.log (userLogged);
});