<?php

/**
 * @var $user
 */

$ddis = [
    '55',
    '1',
    '93',
    '27',
    '355',
    '49',
    '376',
    '244',
    '1',
    '1',
    '966',
    '213',
    '54',
    '374',
    '297',
    '61',
    '43',
    '994',
    '1',
    '973',
    '880',
    '1',
    '375',
    '32',
    '501',
    '229',
    '1',
    '591',
    '387',
    '267',
    '673',
    '359',
    '226',
    '257',
    '975',
    '238',
    '237',
    '855',
    '1',
    '7',
    '235',
    '56',
    '86',
    '357',
    '65',
    '57',
    '269',
    '242',
    '850',
    '82',
    '225',
    '506',
    '385',
    '53',
    '599',
    '45',
    '253',
    '1',
    '20',
    '503',
    '971',
    '593',
    '291',
    '421',
    '386',
    '34',
    '372',
    '251',
    '679',
    '63',
    '358',
    '33',
    '241',
    '220',
    '233',
    '995',
    '350',
    '44',
    '1',
    '30',
    '299',
    '590',
    '1',
    '502',
    '592',
    '594',
    '224',
    '240',
    '245',
    '509',
    '31',
    '504',
    '852',
    '36',
    '967',
    '61',
    '870',
    '262',
    '1',
    '61',
    '682',
    '298',
    '500',
    '1',
    '692',
    '1',
    '672',
    '677',
    '248',
    '690',
    '1',
    '1',
    '1',
    '91',
    '62',
    '98',
    '964',
    '353',
    '354',
    '972',
    '39',
    '1',
    '81',
    '962',
    '686',
    '965',
    '856',
    '266',
    '371',
    '961',
    '231',
    '218',
    '423',
    '370',
    '352',
    '853',
    '389',
    '261',
    '60',
    '265',
    '960',
    '223',
    '356',
    '212',
    '596',
    '230',
    '222',
    '262',
    '52',
    '691',
    '258',
    '373',
    '377',
    '976',
    '382',
    '1',
    '95',
    '264',
    '674',
    '977',
    '505',
    '227',
    '234',
    '683',
    '47',
    '687',
    '64',
    '968',
    '599',
    '680',
    '970',
    '507',
    '675',
    '92',
    '595',
    '51',
    '689',
    '48',
    '1',
    '351',
    '974',
    '254',
    '996',
    '236',
    '243',
    '1',
    '420',
    '40',
    '250',
    '7',
    '212',
    '508',
    '1',
    '685',
    '378',
    '290',
    '1',
    '590',
    '1',
    '590',
    '599',
    '239',
    '1',
    '221',
    '232',
    '381',
    '963',
    '252',
    '94',
    '268',
    '249',
    '211',
    '46',
    '41',
    '597',
    '47',
    '992',
    '66',
    '886',
    '255',
    '246',
    '670',
    '228',
    '676',
    '1',
    '216',
    '993',
    '90',
    '688',
    '380',
    '256',
    '598',
    '998',
    '678',
    '379',
    '58',
    '84',
    '681',
    '260',
    '263',
];

$ddis_unique = array_unique($ddis, SORT_STRING);
$photo = $user->pictures[2]->link ?? $user->photo;
?>

<h2 class="m-2"><?= get_post()->post_title ?></h2>
<div class="container my-5">
    <div id="liveAlertPlaceholder"></div>
    <form id="ings-update-user-form">
        <div>
            <div class="meus-dados-picture-container">
                <img class="rounded-circle mx-auto d-block meus-dados-picture-preview" src="<?= $photo ?>" alt="" id="picture-view">
                <div class="meus-dados-picture-preview-camera-icon">
                    <span class="material-symbols-outlined" style="font-size: 40px;" id="picture-view-icon">
                        photo_camera
                    </span>
                </div>
            </div>
            <input accept="image/*" type="file" name="picture" hidden>
            <p class="text-uppercase text-center">
                <small>
                    foto de perfil
                </small>
            </p>
        </div>
        <input type="hidden" name="action" value="ings_update_user">
        <div class="mb-3">
            <label for="" class="form-label text-uppercase">
                Nome Completo
            </label>
            <input class="form-control" name="name" value="<?= $user->fullName ?>">
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        Nacionalidade <i class="fas fa-lock float-end ms-2"></i>
                    </label>
                    <input class="form-control disabled" value="<?= $user->nationality === 'BRA' ? 'Brasileiro' : 'Estrangeiro' ?>" disabled>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        <?= $user->nationality === 'BRA' ? 'CPF' : 'Passaporte/ID' ?> <i class="fas fa-lock float-end ms-2"></i>
                    </label>
                    <input class="form-control disabled" value="<?= $user->document->number ?>" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        Data de nascimento
                    </label>
                    <input type="date" class="form-control" name="birthdate" value="<?= $user->birthdate ?>">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        Identidade de Gênero
                    </label>
                    <select class="form-select" name="gender">
                        <option id="userGender-O" value="O" <?= $user->gender == 0 ? "selected" : "" ?>>Outro</option>
                        <option id="userGender-FC" value="FC" <?= $user->gender == "FC" ? "selected" : "" ?>>Mulher Cisgênero</option>
                        <option id="userGender-FT" value="FT" <?= $user->gender == "FT" ? "selected" : "" ?>>Mulher Transgênero</option>
                        <option id="userGender-MC" value="MC" <?= $user->gender == "MC" ? "selected" : "" ?>>Homem Cisgênero</option>
                        <option id="userGender-MT" value="MT" <?= $user->gender == "MT" ? "selected" : "" ?>>Homem Transgênero</option>
                        <option id="userGender-NB" value="NB" <?= $user->gender == "NB" ? "selected" : "" ?>>Não-binário</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        E-mail <i class="fas fa-lock float-end ms-2"></i>
                    </label>
                    <input id="disabled-email" type="email" class="form-control disabled" value="<?= $user->email ?>" disabled>
                </div>
            </div>
            <!--<div class="col">
                 <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        DDI <i class="fas fa-lock float-end ms-2"></i>
                    </label>
                    <input class="form-control disabled" value="<?= "" //$ddi 
                                                                ?>" disabled>
                </div>
            </div> -->
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="" class="form-label text-uppercase">
                        Celular <i class="fas fa-lock float-end ms-2"></i>
                    </label>
                    <input id="disabled-phone" class="form-control disabled" value="<?= $user->phone ?>" disabled>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center ings-change-buttons">
            <button type="button" class="btn btn-outline-primary mx-2 rounded-pill" data-button-change="phone">
                Alterar Telefone
            </button>
            <button type="button" class="btn btn-outline-primary mx-2 rounded-pill" data-button-change="email">
                Alterar E-mail
            </button>
            <button type="button" class="btn btn-outline-primary mx-2 rounded-pill" data-button-change="password">
                Alterar Senha
            </button>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <button type="submit" class="btn orange-save btn-lg mx-2 rounded-pill">Salvar</button>
        </div>
    </form>
</div>

<div class="modal" tabindex="-1" id="modal-change-phone">
    <div class="modal-dialog modal-dialog-centered">
        <form id="ings-update-user-phone-form">
            <div class="modal-content">
                <div class="modal-header border-0 text-center d-block">
                    <h5 class="modal-title text-center mb-3">ALTERAR TELEFONE</h5>
                    <p>Digite o novo número de telefone que deseja cadastrar. Não será aceito um número que esteja sendo usado em outra conta Mirante.</p>
                </div>
                <div class="modal-body border-0">
                    <input type="hidden" name="action" value="ings_update_user">
                    <div class="row justify-content-center">
                        <div class="col-4">
                            <small class="text-uppercase">
                                ddi
                            </small>
                            <select class="form-select" name="ddi">
                                <?php foreach ($ddis_unique as $ddi) : ?>
                                    <option value="<?= $ddi ?>">+<?= $ddi ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <small class="text-uppercase">
                                Celular
                            </small>
                            <input class="form-control" name="phone" placeholder="" maxlength="16">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-around">
                    <button type="submit" class="btn orange-continue rounded-pill text-uppercase">Continuar</button>
                    <button type="button" class="btn btn-primary rounded-pill text-uppercase" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal" tabindex="-1" id="modal-change-email">
    <div class="modal-dialog modal-dialog-centered">
        <form id="ings-update-user-email-form">
            <div class="modal-content">
                <div class="modal-header border-0 text-center d-block">
                    <h5 class="modal-title text-center mb-3">ALTERAR E-MAIL</h5>
                    <p>Digite o novo e-mail que deseja cadastrar, não será aceito um e-mail que esteja sendo usado em outra conta Ingresse.</p>
                </div>
                <div class="modal-body border-0">
                    <input type="hidden" name="action" value="ings_update_user">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <small class="text-uppercase">
                                E-mail
                            </small>
                            <input class="form-control" name="email" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-around">
                    <button type="submit" class="btn orange-continue rounded-pill text-uppercase">Continuar</button>
                    <button type="button" class="btn btn-primary rounded-pill text-uppercase" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal" tabindex="-1" id="modal-change-password">
    <div class="modal-dialog modal-dialog-centered">
        <form id="ings-update-user-password-form">
            <div class="modal-content">
                <div class="modal-header border-0 text-center d-block">
                    <h5 class="modal-title text-center mb-3">ALTERAR SENHA</h5>
                </div>
                <div class="modal-body border-0">
                    <p id="ings-update-user-password-form-validation" class="text-danger text-uppercase"></p>
                    <input type="hidden" name="action" value="ings_update_user">
                    <div class="row justify-content-center">
                        <div class="inputpass-change">
                            <small class="text-uppercase">
                                senha atual*
                            </small>
                            <input type="password" class="form-control" name="password" placeholder="" required>
                            <i class="bi bi-eye-slash eye" id="password-eye" onclick="togglePasswordVisibility('password')"></i>
                        </div>
                        <div class="inputpass-change">
                            <small class="text-uppercase">
                                nova senha
                            </small>
                            <input type="password" class="form-control" name="newPassword" placeholder="" required>
                            <i class="bi bi-eye-slash eye" id="newPassword-eye" onclick="togglePasswordVisibility('newPassword')"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-around">
                    <button type="submit" class="btn orange-continue rounded-pill text-uppercase">Continuar</button>
                    <button type="button" class="btn btn-primary rounded-pill text-uppercase" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(() => {
        checkAndApplyPhoneMask(document.querySelector('[name=ddi]'));

        ['phone', 'email', 'password'].forEach(e => {
            jQuery(`button[data-button-change=${e}]`).on('click', () => {
                const modal = jQuery(`#modal-change-${e}`);
                modal.modal('show');
            })
        });

        jQuery('[name=ddi]').on('change', (e) => {
            checkAndApplyPhoneMask(e.target);
        })

        jQuery('form[id]').on('submit', async e => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const pictureInput = jQuery('[name=picture]');

            if (pictureInput.prop('files').length >= 1) {
                const picture = await toBase64(pictureInput.prop('files')[0]);
                formData.set('picture', picture);
            }

            if (formData.get('phone')) {
                const phone = formData.get('phone');
                formData.set('phone', phone.replace(/\D/g, ''));
            }

            jQuery.ajax({
                type: "post",
                url: my_ajax_object.ajax_url,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    const userResponse = JSON.parse(response);
                    const userLogged = JSON.parse(Cookies.get('wp_ing_user'));
                    const {
                        name,
                        birthdate,
                        gender,
                        phone,
                        email,
                        pictures,
                    } = userResponse;

                    const userUpdated = {
                        ...userLogged,
                        ...userResponse,
                        fullName: name,
                        birthdate,
                        gender,
                        phone: phone.ddi + phone.number,
                    }

                    if (pictures) {
                        const photo = userResponse.pictures.filter(p => p.type === 'medium').shift();
                        userUpdated.photo = photo.link;
                    }

                    Cookies.set('wp_ing_user', JSON.stringify(userUpdated));

                    jQuery('#disabled-email').val(email);
                    jQuery('#disabled-phone').val(phone.ddi + phone.number);

                    hideAllModals();
                    appendAlert('Dados atualizados com sucesso!', 'success');
                },
                error: function(jqXhr, textStatus, response) {
                    hideAllModals();
                    appendAlert(jqXhr.responseText, 'danger');
                }
            });
        });

        jQuery('#picture-view-icon').on('click', function() {
            jQuery('[name=picture]').trigger('click');
        });

        jQuery('[name=picture]').on('change', function(e) {
            const [file] = e.target.files
            if (file) {
                jQuery('#picture-view').attr('src', URL.createObjectURL(file));
            }
        });
    });

    function appendAlert(message, type) {
        const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

        const wrapper = document.createElement('div')
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('')

        alertPlaceholder.append(wrapper)
    }

    function checkAndApplyPhoneMask(target) {
        if (target.value === '55') {
            jQuery('[name=phone]').mask('(00) 000000000')
        } else {
            jQuery('[name=phone]').unmask();
        }
    }

    function hideAllModals() {
        const modalsElements = document.querySelectorAll('.modal');

        modalsElements.forEach(e => {
            jQuery(e).modal('hide');
            jQuery(e).find('input').not('[type=hidden]').val('');
        });
    }

    function togglePasswordVisibility(name) {
        console.log(name)
        const password = document.querySelector(`[name=${name}]`);
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        document.querySelector(`#${name}-eye`).classList.toggle('bi-eye');
    }

    const toBase64 = file => new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
    });
</script>