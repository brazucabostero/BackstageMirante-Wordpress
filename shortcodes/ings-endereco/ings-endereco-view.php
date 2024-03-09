<h2 class="m-2">Meu endereço</h2>
<div class="container">
    <div id="liveAlertPlaceholder"></div>
    <p class="text-center">Para tornar o processo de compra mais rápido, preencha os dados abaixo.</p>
    <form id="ings-update-user-address-form">
        <input type="hidden" name="action" value="ings_update_user">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="mb-3">
                    <div class="d-flex justify-content-around">
                        <label for="" class="form-label text-uppercase">
                            CEP*
                        </label>
                        <a class="text-uppercase small ms-auto" href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank"><b>Não sabe seu cep?</b></a>
                    </div>
                    <input class="form-control" name="address[zip]" value="<?= $user->address->zipcode ?>" required>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="" class="form-label text-uppercase">
                    Endereço*
                </label>
                <input class="form-control" name="address[street]" value="<?= $user->address->street ?>" required>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        Numero*
                    </label>
                    <input class="form-control" name="address[number]" value="<?= $user->address->number ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        Complemento
                    </label>
                    <input class="form-control" name="address[complement]" value="<?= $user->address->complement ?>">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        Bairro*
                    </label>
                    <input class="form-control" name="address[district]" value="<?= $user->address->district ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        CIDADE*
                    </label>
                    <input class="form-control" name="address[city]" value="<?= $user->address->city ?>" required>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        Estado*
                    </label>
                    <input class="form-control" name="address[state]" value="<?= $user->address->state ?>" required>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <button type="submit" class="btn orange-save btn-lg mx-2 rounded-pill">Salvar</button>
        </div>
    </form>
</div>
<script>
    jQuery(document).ready(() => {
        jQuery('[name="address[zip]"]').mask('00000-000');
        const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
        const appendAlert = (message, type) => {
            const wrapper = document.createElement('div')
            wrapper.innerHTML = [
                `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                `   <div>${message}</div>`,
                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                '</div>'
            ].join('')

            alertPlaceholder.append(wrapper)
        }
        jQuery('#ings-update-user-address-form').on('submit', e => {
            e.preventDefault();
            const form = jQuery(e.target);
            jQuery.ajax({
                type: "post",
                url: my_ajax_object.ajax_url,
                data: form.serialize(),
                success: function(response) {
                    const responseJson = JSON.parse(response);
                    console.log(responseJson)
                    const userLogged = JSON.parse(Cookies.get('wp_ing_user'));
                    const {
                        address
                    } = responseJson;

                    Cookies.set('wp_ing_user', JSON.stringify({
                        ...userLogged,
                        address
                    }));

                    appendAlert('Dados atualizados com sucesso!', 'success');
                },
            });
        })
    });
</script>