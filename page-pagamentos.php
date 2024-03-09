<?php get_header();?>
<style>
  body{
    background-color: black;
  }
</style>

<!-- COMPONENTE PARA FLUXO DE PAGAMENTO -->
  <section class="ings-payment-type">
    <div class="container">
      <div class="row">
        <article class="col-md-12">
          <h3>Meio de pagamento</h3>
          <ul class="ings-payment-type-tabs" id="ings-payment" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="active" id="card-credit" data-bs-toggle="tab" data-bs-target="#card-credit-pane" type="button" role="tab" aria-controls="card-credit-pane" aria-selected="true">
                Crédito
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pix" data-bs-toggle="tab" data-bs-target="#pix-pane" type="button" role="tab" aria-controls="pix-pane" aria-selected="false">
                Pix
              </button>
            </li>
          </ul>
          <div class="tab-content" id="ings-payment-content">
            <div class="tab-pane fade show active" id="card-credit-pane" role="tabpanel" aria-labelledby="card-credit" tabindex="0">
              <span class="text-black-70">Quais os dados do seu cartão de crédito?</span>
              <ul class="credit-flags">
                <li>
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M38 0H2C0.89543 0 0 0.89543 0 2V28C0 29.1046 0.89543 30 2 30H38C39.1046 30 40 29.1046 40 28V2C40 0.89543 39.1046 0 38 0Z" fill="#006E95"/>
                </svg>
                </li>
                <li>
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M38 0H2C0.89543 0 0 0.89543 0 2V28C0 29.1046 0.89543 30 2 30H38C39.1046 30 40 29.1046 40 28V2C40 0.89543 39.1046 0 38 0Z" fill="#006E95"/>
                </svg>
                </li>
                <li>
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M38 0H2C0.89543 0 0 0.89543 0 2V28C0 29.1046 0.89543 30 2 30H38C39.1046 30 40 29.1046 40 28V2C40 0.89543 39.1046 0 38 0Z" fill="#006E95"/>
                </svg>
                </li>
                <li>
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M38 0H2C0.89543 0 0 0.89543 0 2V28C0 29.1046 0.89543 30 2 30H38C39.1046 30 40 29.1046 40 28V2C40 0.89543 39.1046 0 38 0Z" fill="#006E95"/>
                </svg>
                </li>
                <li>
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M38 0H2C0.89543 0 0 0.89543 0 2V28C0 29.1046 0.89543 30 2 30H38C39.1046 30 40 29.1046 40 28V2C40 0.89543 39.1046 0 38 0Z" fill="#006E95"/>
                </svg>
                </li>
                <li>
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M38 0H2C0.89543 0 0 0.89543 0 2V28C0 29.1046 0.89543 30 2 30H38C39.1046 30 40 29.1046 40 28V2C40 0.89543 39.1046 0 38 0Z" fill="#006E95"/>
                </svg>
                </li>
                <li>
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M38 0H2C0.89543 0 0 0.89543 0 2V28C0 29.1046 0.89543 30 2 30H38C39.1046 30 40 29.1046 40 28V2C40 0.89543 39.1046 0 38 0Z" fill="#006E95"/>
                </svg>
                </li>
                <li>
                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M38 0H2C0.89543 0 0 0.89543 0 2V28C0 29.1046 0.89543 30 2 30H38C39.1046 30 40 29.1046 40 28V2C40 0.89543 39.1046 0 38 0Z" fill="#006E95"/>
                </svg>
                </li>
              </ul>
              <form action="" class="form-card-credit">
                <ul>
                  <li>
                    <label>Número do cartão</label>
                    <input type="text" placeholder="__ __ __ __">
                  </li>
                  <li>
                    <label>Validade</label>
                    <input type="text" placeholder="__/__">
                  </li>
                  <li>
                    <label>Cód. Segurança</label>
                    <input type="text" placeholder="CVV">
                  </li>
                  <li>
                    <label>Nome impresso no cartão</label>
                    <input type="text" placeholder="EX: João M Santos">
                  </li>
                  <li>
                    <label>Cpf</label>
                    <input type="text" placeholder="___ ___ ___-__">
                  </li>
                  <li>
                    <label>Nascimento</label>
                    <input type="text" placeholder="__/__/___">
                  </li>
                  <li>
                    <label>Parcelamento</label>
                    <select name="" id="">
                      <option>1 x de R$ 1.254,00</option>
                      <option>2 x de R$ 1.254,00</option>
                      <option>3 x de R$ 1.254,00</option>
                    </select>
                  </li>
                </ul>
                <a class="btn-salvar" href="#">
                  Salvar
                </a>
              </form>
              <div class="one-click-buy">
                <div>
                  <h4>COMPRA COM 1-CLIQUE</h4>
                  <span>Ative e na próxima vez, compre sem preencher os dados</span>
                </div>
                <div>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked>
                </div>
                </div>
              </div>
              <span class="span.text-white-70">
                Podemos fazer uma cobrança de baixo valor durante a verificação do seu cartão. A mesma será estornada automaticamente.
              </span>
            </div>
            <div class="tab-pane fade" id="pix-pane" role="tabpanel" aria-labelledby="pix" tabindex="0">
              PIX
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>

<!-- COMPONENTE ENDEREÇO DE COBRANÇA --> 
  <section class="ings-payment-adress">
    <div class="container">
      <div class="row">
        <article class="col-md-12">
          <h3>Endereço de cobrança</h3>
          <span class="text-white-70">Precisamos do seu endereço apenas para garantir a segurança do seu pagamento</span>
        </article>
      </div>
      <div class="row">
        <article class="col-md-12">
          <div class="current-adress">
            
          </div>
        </article>
      </div>
    </div>
  </section>
