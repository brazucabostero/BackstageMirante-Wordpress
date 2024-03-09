<!doctype html>
<html lang="en" ng-app="myTickets">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js">
  </script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular-cookies.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular-route.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-i18n/1.8.2/angular-locale_pt-br.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let x = new bootstrap.Carousel('#carouselExampleIndicators');
      //var myModal = new bootstrap.Modal(document.getElementById("modalUserTransfer"), {});
    });
  </script>
  <script>
    var app = angular.module('myTickets', ['ngCookies', 'ngRoute']);
    app.config(function($routeProvider) {
      $routeProvider.when("/", {
        template: `
        <div class="container">
          <div class="row">
            <div class="col-6 header-carteira">
                <p class="carteira">Carteira de ingressos</p>
            </div>
            <div class="col-6 header-buttons">
              <button type="button" ng-class="{'btn-active': prev}" class="btn-prev" ng-click="previousEvents()">Anteriores</button>
              <button type="button" ng-class="{'btn-active': next}" class="btn-next" ng-click="nextEvents()">Próximos</button>
            </div>
          </div>
          <div class="row">
              <div class="col-12 pendentes" ng-click="setEventsPending()" ng-if="showPending">
                <i class="bi bi-bell" style="float:none!important"></i>{{ticketsPendingMessage}}
              </div>
          </div>
            <div class="row container-events">
              <div class="col-12 events-null" ng-if="!eventsList.length > 0">
                Não existem eventos no momento
              </div>
              
              <div ng-click="detailEvent(event, event.sessions.data[0])" class="col-md-6 col-sm-12 event" ng-repeat="event in eventsList">
                <div class="row">
                    <div class="col-12 carteira-img-container"><img class="carteira-img-event" src="{{event.poster.replace('medium', 'large')}}"></div>
                </div>
                <div class="row">
                    <div class="col-12 ingressos-container">
                        <div class="ingressos">{{event.tickets}} {{event.tickets > 1 ? 'ingressos' : 'ingresso'}} para este evento</div>
                    </div>
                </div>
                <div class="wallet-event-card-infos">
                  <div class="date-event-list">
                    <div ng-click="detailEvent(event, session)" ng-repeat="session in event.sessions.data" class="date-event">
                      {{convertDataSession(session)}}
                    </div>
                  </div>
                  <div class="infos-event">
                    <p class="event-name">{{ event.title }}</p>
                    <p class="event-time"><i class="bi bi-clock"></i> {{ event.sessions.data[0].datetime | date : "shortTime" }}</p>
                    <p class="event-local">
                      <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.00006 8.67733V8.67733C4.75731 8.67733 3.75006 7.66121 3.75006 6.40751V6.40751C3.75006 5.15382 4.75731 4.1377 6.00006 4.1377V4.1377C7.24281 4.1377 8.25006 5.15382 8.25006 6.40751V6.40751C8.25006 7.66121 7.24281 8.67733 6.00006 8.67733Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.00006 14.7302C6.00006 14.7302 0.750061 10.3797 0.750061 6.40757C0.750061 3.48253 3.10056 1.11133 6.00006 1.11133C8.89956 1.11133 11.2501 3.48253 11.2501 6.40757C11.2501 10.3797 6.00006 14.7302 6.00006 14.7302Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                        {{event.venue.street}} - {{event.venue.city}}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>`,
        controller: 'MyTicketsController'
      }).when("/details-event/:id", {
        template: `<style>
        .custom-tooltip {
          position: relative;
          display: inline-block;
          border-bottom: 1px dotted black;
        }

        .custom-tooltip .tooltiptext {
          visibility: hidden;
          background-color: #555;
          color: #fff;
          text-align: center;
          border-radius: 6px;
          padding: 5px;
          position: absolute;
          z-index: 1;
          bottom: 125%;
          left: 50%;
          margin-left: -60px;
          opacity: 0;
          transition: opacity 0.3s;
          text-transform: none;
          font-size: 10px;
        }

        .custom-tooltip .tooltiptext::after {
          content: "";
          position: absolute;
          top: 100%;
          left: 50%;
          margin-left: -5px;
          border-width: 5px;
          border-style: solid;
          border-color: #555 transparent transparent transparent;
        }

        .custom-tooltip:hover .tooltiptext {
          visibility: visible;
          opacity: 1;
        }
        .btn-close:hover {
          background-color: white !important;
        }


        .modal-custom {
          display: none; 
          position: fixed;
          z-index: 1;
          padding-top: 100px;
          left: 0;
          top: 0;
          width: 100%; 
          height: 100%; 
          overflow: auto; 
          background-color: rgb(0,0,0); 
          background-color: rgba(0,0,0,0.4); 
        }

        .custom {
          background-color: #fefefe;
          margin: auto;
          padding: 20px;
          border: 1px solid #888;
          width: 330px;
          position: relative;
        }

        /* The Close Button */
        .close {
          color: #aaaaaa;
          width: 16px;
          font-size: 28px;
          font-weight: bold;
          position: absolute;
          right: 10px;
        }

        .close:hover,
        .close:focus {
          color: red;
          text-decoration: none;
          cursor: pointer;
        }

        .actions {
          display: flex;
          flex-wrap: nowrap;
          flex-direction: row-reverse;
          justify-content: space-around;
        }

        .friend-not-found {
          display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            gap: 15px;
        }

        .actions button {
          height: 40px;
          width: 130px;
          text-transform: capitalize;
        }

        .italic {
          font-style: italic;
        }


            @media (max-width:767px) {
                .elementor-location-header {
                    display: none
                }

                .tickets-mobile {
                    height: auto;
                    min-height: 1000px
                }

                .tickets-poster-container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .tickets-poster-container img {
                    width: auto!important;
                }
            }
        </style>
        <div id="myModal" class="modal-custom">
          <div class="modal-content custom">
            <span ng-click="returnTicket(null)" class="close">&times;</span>
            <h4>Atenção</h4>
            <p>Deseja confirmar a devolução do ingresso?</p>
            <div class="actions">
            <button ng-click="returnTicket(null)" class="btn btn-danger" >
                <i class="bi bi-x"></i>
                Cancelar
              </button>
              <button ng-click="confirmReturnTicket()" class="btn btn-primary" >
                <i class="bi bi-check2"></i>
                Confirmar
              </button>
            </div>
          </div>

        </div>
        <div class="container show-wheb">
            <div class="row">
                <div class="col-12 back"><button type="button" class="btn btn-link btn-back" ng-click="back()"><i
                            class="bi bi-chevron-left"></i>Voltar</button></div>
            </div>
            <div class="row">
                <div class="col-2"><img src="{{eventSelected.poster}}"></div>
                <div class="col-10">
                    <p class="seus-ingressos">Seus ingressos para <strong>{{eventSelected.title}}</strong></p>
                    <p class="local"><i class="bi bi-geo-alt-fill"></i> {{eventSelected.venue.street}} - {{eventSelected.venue.city}}</p>
                    <div class="date-event-list">
                      <div ng-click="detailEvent(date)" ng-repeat="date in ticketsSessions" data-session="{{date}}" class="date-event-datail  {{isActiveDate(date)}} ">
                          {{convertStringData(date)}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ingressos-text" ng-if="ticketsReceived.length > 0">
                <div class="col-12">
                    <p class="disponiveis-text"> Ingressos recebidos</p>
                </div>
            </div>
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item" ng-repeat="ticket in ticketsReceived">
                    <h2 class="accordion-header" id="panelsStayOpen-heading{{$index}}">
                      <button class="accordion-button button-ingresso {{ticket.transferedTo ? 'transfered' : '' }}"
                            type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse{{$index}}"
                             aria-controls="panelsStayOpen-collapse{{$index}}">
                             <div class="ings-tickets-sections">
                              <p class="pista">{{ticket.title}}</p>
                              <p class="pista">{{ticket.type}}</p>
                              <div>
                              <div class="custom-tooltip">
                              <span class="tooltiptext">Devolver ingresso</span>
                                <div class="btn-retornar" ng-click="returnTicket(ticket)" ng-if="ticket.isReturnable">
                                  <i class="bi bi-arrow-counterclockwise"></i>
                                </div>
                              </div>
                      </button>
                    </h2>
                    <div id="panelsStayOpen-collapse{{$index}}" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-heading{{$index}}">
                        <div class="accordion-body">
                            <div class="row">
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status !== 'accepted'" class="col-4">
                                    <p class="titular-text">Titular</p>
                                    <p class="titular-name">{{ticket.currentHolder.name}}</p>
                                </div>
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-4">
                                    <p class="titular-text">Titular</p>
                                    <p class="titular-name">{{ticket.transferedTo.name}}</p>
                                </div>
                                <div ng-if="!ticket.transferedTo || ticket.transferedTo.status !== 'accepted'" class="col-4">
                                    <p class="cpf-text">{{user.identity.type.name}}</p>
                                    <p class="cpf">{{user.identity.id}}</p>
                                </div>
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-4">
                                    <p class="cpf-text">E-mail</p>
                                    <p class="cpf">{{ticket.transferedTo.email}}</p>
                                </div>
                                <div class="col-4 ">
                                  <button ng-if="ticket.transferedTo.status === 'pending'" ng-click="transferCancelTicket(ticket)"
                                    class="btn btn-primary float-end btn-cancelar">CANCELAR TRANSFERÊNCIA
                                  </button>
                                  <button ng-if="!ticket.transferedTo && ticket.isTransferable" ng-click="setTicket(ticket)" class="btn btn-primary float-end btn-transferir" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="bi bi-arrow-left-right"></i>
                                    Transferir
                                  </button>
                                  </div>
                            </div>
                            <div class="row">
                                <div class="col-12 qr-mobile"> <i class="bi bi-qr-code"></i> Consulte as versões mobile ou app
                                    para visualizar o QR Code </div>
                                <div class="col-12 container-qr"><img class="show-qr-code"
                                        src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl={{ticket.code}}&coe=UTF-8">
                                </div>
                            </div>
<!--                            <div class="row"> -->
<!--                                <div class="col-12 infos" ng-bind-html="trustHtml(ticket.description)"></div>-->
<!--                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ingressos-text" ng-if="ticketsAvaliable.length > 0">
                <div class="col-12">
                    <p class="disponiveis-text"> Ingressos disponíveis</p>
                </div>
            </div>
            <div class="accordion" id="accordionavaliableOpenExample">
                <div class="accordion-item" ng-repeat="ticket in ticketsAvaliable">
                    <h2 class="accordion-header" id="avaliableOpen-heading{{$index}}">
                      <button class="accordion-button button-ingresso {{ticket.transferedTo ? 'transfered' : '' }}"
                            type="button" data-bs-toggle="collapse" data-bs-target="#avaliableOpen-collapse{{$index}}"
                             aria-controls="avaliableOpen-collapse{{$index}}">
                             <div class="ings-tickets-sections">
                              <p class="pista">{{ticket.title}}</p>
                              <p class="pista">{{ticket.type}}</p>
                              <div>
                              <div class="custom-tooltip">
                              <span class="tooltiptext">Devolver ingresso</span>
                                <div class="btn-retornar" ng-click="returnTicket(ticket)" ng-if="ticket.isReturnable">
                                  <i class="bi bi-arrow-counterclockwise"></i>
                                </div>
                              </div>
                      </button>
                    </h2>
                    <div id="avaliableOpen-collapse{{$index}}" class="accordion-collapse collapse"
                        aria-labelledby="avaliableOpen-heading{{$index}}">
                        <div class="accordion-body">
                            <div class="row">
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status !== 'accepted'" class="col-4">
                                    <p class="titular-text">Titular</p>
                                    <p class="titular-name">{{ticket.currentHolder.name}}</p>
                                </div>
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-4">
                                    <p class="titular-text">Titular</p>
                                    <p class="titular-name">{{ticket.transferedTo.name}}</p>
                                </div>
                                <div ng-if="!ticket.transferedTo || ticket.transferedTo.status !== 'accepted'" class="col-4">
                                    <p class="cpf-text">{{user.identity.type.name}}</p>
                                    <p class="cpf">{{user.identity.id}}</p>
                                </div>
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-4">
                                    <p class="cpf-text">E-mail</p>
                                    <p class="cpf">{{ticket.transferedTo.email}}</p>
                                </div>
                                <div class="col-4 ">
                                  <button ng-if="ticket.transferedTo.status === 'pending'" ng-click="transferCancelTicket(ticket)"
                                    class="btn btn-primary float-end btn-cancelar">CANCELAR TRANSFERÊNCIA
                                  </button>
                                  <button ng-if="!ticket.transferedTo && ticket.isTransferable" ng-click="setTicket(ticket)" class="btn btn-primary float-end btn-transferir" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="bi bi-arrow-left-right"></i>
                                    Transferir
                                  </button>
                                  </div>
                            </div>
                            <div class="row">
                                <div class="col-12 qr-mobile"> <i class="bi bi-qr-code"></i> Consulte as versões mobile ou app
                                    para visualizar o QR Code </div>
                                <div class="col-12 container-qr"><img class="show-qr-code"
                                        src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl={{ticket.code}}&coe=UTF-8">
                                </div>
                            </div>
<!--                            <div class="row"> -->
<!--                                <div class="col-12 infos" ng-bind-html="trustHtml(ticket.description)"></div>-->
<!--                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ingressos-text" ng-if="ticketsOnTransfereing.length > 0">
                <div class="col-12">
                    <p class="disponiveis-text"> Ingressos em transferência <span class="italic">(aguardando aceite)</span></p>
                </div>
            </div>
            <div class="accordion" id="accordionTransferingOpenExample">
                <div class="accordion-item" ng-repeat="ticket in ticketsOnTransfereing">
                    <h2 class="accordion-header" id="TransferingOpen-heading{{$index}}">
                      <button class="accordion-button button-ingresso {{ticket.transferedTo ? 'transfered' : '' }}"
                            type="button" data-bs-toggle="collapse" data-bs-target="#TransferingOpen-collapse{{$index}}"
                             aria-controls="TransferingOpen-collapse{{$index}}">
                             <div class="ings-tickets-sections">
                              <p class="pista">{{ticket.title}}</p>
                              <p class="pista">{{ticket.type}}</p>
                              <div>
                              <div class="custom-tooltip">
                              <span class="tooltiptext">Devolver ingresso</span>
                                <div class="btn-retornar" ng-click="returnTicket(ticket)" ng-if="ticket.isReturnable">
                                  <i class="bi bi-arrow-counterclockwise"></i>
                                </div>
                              </div>
                      </button>
                    </h2>
                    <div id="TransferingOpen-collapse{{$index}}" class="accordion-collapse collapse"
                        aria-labelledby="TransferingOpen-heading{{$index}}">
                        <div class="accordion-body">
                            <div class="row">
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status !== 'accepted'" class="col-4">
                                    <p class="titular-text">Titular</p>
                                    <p class="titular-name">{{ticket.currentHolder.name}}</p>
                                </div>
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-4">
                                    <p class="titular-text">Titular</p>
                                    <p class="titular-name">{{ticket.transferedTo.name}}</p>
                                </div>
                                <div ng-if="!ticket.transferedTo || ticket.transferedTo.status !== 'accepted'" class="col-4">
                                    <p class="cpf-text">{{user.identity.type.name}}</p>
                                    <p class="cpf">{{user.identity.id}}</p>
                                </div>
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-4">
                                    <p class="cpf-text">E-mail</p>
                                    <p class="cpf">{{ticket.transferedTo.email}}</p>
                                </div>
                                <div class="col-4 ">
                                  <button ng-if="ticket.transferedTo.status === 'pending'" ng-click="transferCancelTicket(ticket)"
                                    class="btn btn-primary float-end btn-cancelar">CANCELAR TRANSFERÊNCIA
                                  </button>
                                  <button ng-if="!ticket.transferedTo && ticket.isTransferable" ng-click="setTicket(ticket)" class="btn btn-primary float-end btn-transferir" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="bi bi-arrow-left-right"></i>
                                    Transferir
                                  </button>
                                  </div>
                            </div>
                            <div class="row">
                                <div class="col-12 qr-mobile"> <i class="bi bi-qr-code"></i> Consulte as versões mobile ou app
                                    para visualizar o QR Code </div>
                                <div class="col-12 container-qr"><img class="show-qr-code"
                                        src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl={{ticket.code}}&coe=UTF-8">
                                </div>
                            </div>
<!--                            <div class="row"> -->
<!--                                <div class="col-12 infos" ng-bind-html="trustHtml(ticket.description)"></div>-->
<!--                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ingressos-text" ng-if="ticketsTransfered.length > 0">
                <div class="col-12">
                    <p class="disponiveis-text"> Ingressos enviados</p>
                </div>
            </div>
            <div class="accordion" id="accordionSendedOpenExample">
                <div class="accordion-item" ng-repeat="ticket in ticketsTransfered">
                    <h2 class="accordion-header" id="SendedOpen-heading{{$index}}">
                      <button class="accordion-button button-ingresso {{ticket.transferedTo ? 'transfered' : '' }}"
                            type="button" data-bs-toggle="collapse" data-bs-target="#SendedOpen-collapse{{$index}}"
                             aria-controls="SendedOpen-collapse{{$index}}">
                             <div class="ings-tickets-sections">
                              <p class="pista">{{ticket.title}}</p>
                              <p class="pista">{{ticket.type}}</p>
                              <div>
                              <div class="custom-tooltip">
                              <span class="tooltiptext">Devolver ingresso</span>
                                <div class="btn-retornar" ng-click="returnTicket(ticket)" ng-if="ticket.isReturnable">
                                  <i class="bi bi-arrow-counterclockwise"></i>
                                </div>
                              </div>
                      </button>
                    </h2>
                    <div id="SendedOpen-collapse{{$index}}" class="accordion-collapse collapse"
                        aria-labelledby="SendedOpen-heading{{$index}}">
                        <div class="accordion-body">
                            <div class="row">
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status !== 'accepted'" class="col-4">
                                    <p class="titular-text">Titular</p>
                                    <p class="titular-name">{{ticket.currentHolder.name}}</p>
                                </div>
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-4">
                                    <p class="titular-text">Titular</p>
                                    <p class="titular-name">{{ticket.transferedTo.name}}</p>
                                </div>
                                <div ng-if="!ticket.transferedTo || ticket.transferedTo.status !== 'accepted'" class="col-4">
                                    <p class="cpf-text">{{user.identity.type.name}}</p>
                                    <p class="cpf">{{user.identity.id}}</p>
                                </div>
                                <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-4">
                                    <p class="cpf-text">E-mail</p>
                                    <p class="cpf">{{ticket.transferedTo.email}}</p>
                                </div>
                                <div class="col-4 ">
                                  <button ng-if="ticket.transferedTo.status === 'pending'" ng-click="transferCancelTicket(ticket)"
                                    class="btn btn-primary float-end btn-cancelar">CANCELAR TRANSFERÊNCIA
                                  </button>
                                  <button ng-if="!ticket.transferedTo && ticket.isTransferable" ng-click="setTicket(ticket)" class="btn btn-primary float-end btn-transferir" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="bi bi-arrow-left-right"></i>
                                    Transferir
                                  </button>
                                  </div>
                            </div>
                            <div class="row">
                                <div class="col-12 qr-mobile"> <i class="bi bi-qr-code"></i> Consulte as versões mobile ou app
                                    para visualizar o QR Code </div>
                                <div class="col-12 container-qr"><img class="show-qr-code"
                                        src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl={{ticket.code}}&coe=UTF-8">
                                </div>
                            </div>
<!--                            <div class="row"> -->
<!--                                <div class="col-12 infos" ng-bind-html="trustHtml(ticket.description)"></div>-->
<!--                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container show-mobile tickets-mobile">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-touch="true">
                <div class="carousel-indicators"><button ng-repeat="ticket in ticketsMobile" type="button"
                        data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$index}}"
                        ng-class="{'active': $index == 0}" aria-current="true" aria-label="Slide {{$index}}"></button></div>
                <div class="carousel-inner">
                    <div class="carousel-item" ng-class="{'active': $index == 0}" ng-repeat="ticket in ticketsMobile"><button
                            ng-click="back()" type="button" class="btn-back-mobile"><i
                                class="bi bi-chevron-left"></i></button>
                                <div class="tickets-poster-container">
                                  <img src="{{eventSelected.poster.replace('medium', 'large')}}" class="ticket-image-mobile">
                                </div>
                        <div class="ticket-info-mobile">
                            <div class="ticket-info-heade-mobile">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="ticket-info-datetime-mobile">{{convertData(ticket.sessions)}}</div>
                                    </div>
                                    <div class="col-9">
                                        <p class="ticket-location-mobile">Allianz Parque - São Paulo</p>
                                        <p class="ticket-event-time">{{ ticket.sessions.data[0].datetime | date : "shortTime" }}</p><a href="#"
                                            ng-click="openMap(eventSelected.venue)" class="ticket-event-location">VER NO
                                            MAPA</a>
                                    </div>
                                    <div ng-if="!ticket.transferedTo || ticket.transferedTo.status === 'pending'" class="col-12 "><img class="show-qr-code ticket-qr-code-mobile"
                                            src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl={{ticket.code}}&coe=UTF-8">
                                    </div>
                                    <div class="col-12 pt-4">
                                        <p class="ticket-setor-mobile">{{ticket.title}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <p class="titular-text">Titular</p>
                                        <p class="titular-name mobile-text">{{ticket.currentHolder.name}}</p>
                                    </div>
                                    <div ng-if="!ticket.transferedTo || ticket.transferedTo.status !== 'accepted'" class="col-md-6 col-ms-12">
                                        <p class="cpf-text">{{user.identity.type.name}}
                                        <div class="btn-retornar-mobile" ng-click="returnTicket(ticket)" ng-if="ticket.isReturnable">
                                              <i class="bi bi-arrow-counterclockwise"></i>
                                            </div>
                                        </p>
                                        <p class="cpf">{{user.identity.id}}</p>
                                    </div>
                                    <div ng-if="ticket.transferedTo && ticket.transferedTo.status === 'accepted'" class="col-md-6 col-sm-12">
                                        <p class="cpf-text">E-mail</p>
                                        <p class="cpf mobile-text">{{ticket.currentHolder.email}}</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                      <button ng-if="!ticket.transferedTo && ticket.isTransferable"
                                            ng-click="setTicket(ticket)" class="btn btn-primary btn-transferir-mobile"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">Transferir</button><button
                                            ng-if="ticket.transferedTo.status === 'pending'" ng-click="transferCancelTicket(ticket)"
                                            class="btn btn-primary btn-cancelar-mobile">CANCELAR TRANSFERÊNCIA</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><button class="carousel-control-prev" style="display: none;" type="button"
                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev"><span class="carousel-control-prev-icon"
                        aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button
                    class="carousel-control-next" style="display: none;" type="button"
                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next"><span class="carousel-control-next-icon"
                        aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Transferir ingresso</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-inline" name="searchForm">
                            <div class="row">
                                <div class="col-12 ings-transfer-search">
                                    <div class="form-group">
                                        <input type="text" ng-model="search.email" required class="form-control campo-buscar"
                                            id="inputPassword2" placeholder="Pesquise ">
                                    </div>
                                
                                    <button type="submit" class="btn btn-primary btn-search"
                                        ng-click="searchUser(searchForm)">BUSCAR</button>
                                </div>
                            </div>
                            <div id="user-not-found" class="d-none friend-not-found" ng-if="users.length === 0">
                              Nenhum amigo encontrado
                              <button type="submit" class="btn btn-primary btn-search"
                                        ng-click="sendToUserWithoutAccount(searchForm)">ENVIAR PARA EMAIL</button>
                            </div>
                            <div class="row list-users" ng-if="users.length > 0">
                                <div class="col-12 text-user-title">
                                    Usuários
                                </div>
                                <div class="col-12">
                                    <div class="row list-item" ng-repeat="user in users">
                                        <div class="col-4 user-name">
                                            Nome: {{user.name}}
                                        </div>
                                        <div class="col-4 user-email">
                                            {{user.email}}
                                        </div>
                                        <div class="col-4 user-select">
                                            <button type="submit" class="btn btn-primary btn-selecionar"
                                                ng-click="transferTicket(user)">SELECIONAR</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>`,
        controller: 'DetailController'
      }).when("/pending", {
        template: `
        <style>
        .bell-icon {
          position: absolute;
          background: orange;
          width: 40px;
          height: 40px;
          border-radius: 30px;
          display: flex;
          right: 20px;
          top: 5px;
          justify-content: center;
          align-items: center;
         
        }
        .bell-icon i  {
          padding-right: 0 !important;
        }
        .relative {
          font-size: 16px;
          position: relative;
        }
        </style>
        <div class="container">
          <div class="row" style="margin-bottom: 0px;">
            <div class="col-12 topo-eventos">
              <p class="carteira">Carteira de ingressos</p>
            </div>
          </div>
          <div class="row">
            <div class="col-12 pendentes" ng-click="setEventsPending()">
              <i class="bi bi-bell" style="float: none!important;"></i> {{ticketsPendingMessage}}
            </div>
            <div class="col-md-12">
              <button ng-click="backHome()" class="undo-filter">Desfazer filtro</button>
            </div>
          </div>
          <div class="row container-events">
            <div ng-click="detailEvent(event)" class="col-md-6 col-sm-12 event" ng-repeat="event in eventsPending">
              <div class="row relative">
                <div class="col-12">
                  <img class="carteira-img-event" src="{{event.event.poster.replace('medium', 'large')}}">
                </div>
                <div class="col-12 ingressos-container">
                        <div class="ingressos">1 ingresso para este evento</div>
                    </div>
                <div class="bell-icon">
                  <i class="bi bi-bell"></i>
                </div>
              </div>
              <div class="wallet-event-card-infos">
                <div class="date-event-list date-event">{{convertData(event.sessions)}}</div>
                <div class="infos-event">
                  <p class="event-name">{{ event.event.title }}</p>
                  <p class="event-time">
                    <i class="bi bi-clock"></i> {{ event.sessions.data[0].datetime | date :"shortTime" }}
                  </p>
                  <p class="event-local">
                    <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M6.00006 8.67733V8.67733C4.75731 8.67733 3.75006 7.66121 3.75006 6.40751V6.40751C3.75006 5.15382 4.75731 4.1377 6.00006 4.1377V4.1377C7.24281 4.1377 8.25006 5.15382 8.25006 6.40751V6.40751C8.25006 7.66121 7.24281 8.67733 6.00006 8.67733Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M6.00006 14.7302C6.00006 14.7302 0.750061 10.3797 0.750061 6.40757C0.750061 3.48253 3.10056 1.11133 6.00006 1.11133C8.89956 1.11133 11.2501 3.48253 11.2501 6.40757C11.2501 10.3797 6.00006 14.7302 6.00006 14.7302Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>Allianz Parque - São Paulo
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <style>
            .undo-filter {
              width: 100%;
              height: 40px;
              padding: 4px 8px 4px 8px;
              border-radius: 4px;
              background: rgba(247, 160, 0, 1);
              color: white;
              border: none;
              margin-top: 20px;
              margin-bottom:20px;
            }
          @media (max-width: 767px) {
            .undo-filter {
              width: 100%;
              height: 40px;
              padding: 4px 8px 4px 8px;
              border-radius: 4px;
              background: rgba(247, 160, 0, 1);
              color: white;
              border: none;
              margin-top: 20px;
              margin-bottom:20px;
            }
          }
        </style>
        `,
        controller: 'MyTicketsPendingController'
      }).when("/detail-pending/:id", {
        template: `
        <style>
          @media (max-width:767px) {
            .elementor-location-header {
              display: none
            }

            .tickets-mobile {
              height: auto;
              min-height: 1000px
            }
          }
        </style>
        <div class="container show-wheb">
          <div class="row">
            <div class="col-12 back">
              <button type="button" class="btn btn-link btn-back" ng-click="back()">
                <i class="bi bi-chevron-left"></i>Voltar </button>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <img src="{{eventSelected.poster}}">
            </div>
            <div class="col-10">
              <p class="seus-ingressos">Seus ingreesos para <strong>{{eventSelected.title}}</strong>
              </p>
              <p class="local">
                <i class="bi bi-geo-alt-fill"></i> Allianz Parque - São Paulo
              </p>
              <div class="date-event-list">
                  <div ng-repeat="date in ticketsSessions" data-session="{{date}}" class="date-event-datail">
                      {{convertStringData(date)}}
                    </div>

                </div>
            </div>
          </div>
          <div class="row ingressos-text">
            <div class="col-12">
              <p class="disponiveis-text"></p> Ingressos recebidos
            </div>
          </div>
          <div class="row list-tickets-pending" ng-repeat="ticket in ticketsList">
            <div class="col-4 ticket-pending">
              <p class="name-enviado-texto">Enviado por</p>
              <p class="name-enviado">{{ticket.receivedFrom.name}}</p>
            </div>
            <div class="col-2 ticket-pending">
              <p class="name-ticket">{{ticket.ticket.name}}</p>
            </div>
            <div class="col-6 ticket-pending ticket-transfer-actions">
              <button type="button" class="btn-recusar" ng-click="setTicketRefuse(ticket)">RECUSAR</button>
              <button type="button" class="btn-aceitar" ng-click="setTicketAccept(ticket)">ACEITAR</button>
              <button type="button" class="btn-bell">
                <i class="bi bi-bell"></i>
              </button>
            </div>
          </div>
        </div>
        <style>
          @media (max-width:767px) {
            .seus-ingressos-mobile {
              font-family: Encode Sans !important;
              font-size: 16px !important;
              font-weight: 500 !important;
              line-height: 20px !important;
              letter-spacing: 0em !important;
              text-align: center !important
            }

            .ingresso-pendente-mobile {
              font-family: Encode Sans;
              font-size: 26px;
              font-weight: 700;
              line-height: 33px;
              letter-spacing: 0em;
              text-align: center;
              text-transform: uppercase
            }

            .disponiveis-text-mobile {
              font-family: Encode Sans !important;
              font-size: 18px !important;
              font-weight: 600 !important;
              line-height: 23px !important;
              letter-spacing: 0em !important;
              text-align: left !important;
              color: rgba(167, 167, 167) !important
            }

            .name-enviado-texto {
              font-family: Encode Sans !important;
              font-size: 10px !important;
              font-weight: 500 !important;
              line-height: 13px !important;
              letter-spacing: 0em !important;
              text-align: left !important
            }

            .name-enviado {
              font-family: Encode Sans !important;
              font-size: 12px !important;
              font-weight: 600 !important;
              line-height: 15px !important;
              letter-spacing: 0em !important;
              text-align: left !important
            }

            .name-ticket {
              font-family: Encode Sans !important;
              font-size: 14px !important;
              font-weight: 600 !important;
              line-height: 18px !important;
              letter-spacing: 0em !important;
              margin: 0;
            }

            .ingressos-text {
              margin-bottom: 10px !important;
              margin-top: 30px !important
            }

            .date-mobile-pendente {
              width: 60px !important;
              height: 100px !important;
              padding: 14px !important;
              border-radius: 45px !important;
              gap: 10px !important;
              font-family: Encode Sans !important;
              font-size: 16px !important;
              font-weight: 700 !important;
              line-height: 24px !important;
              letter-spacing: 0.1em !important;
              text-align: center !important;
              border: 1px solid rgba(255, 255, 255, 1) !important
            }

            .ticket-info-mobile {
              background: black !important;
              width: 100% !important;
              height: 100% !important;
              margin-top: -50px !important;
              border-radius: 40px 40px 10px 10px !important;
              position: relative !important;
              min-height: 700px !important
            }
        </style>
        <div class="container show-mobile tickets-mobile">
          <div id="carouselExampleIndicators" class="carousel slide" data-bs-touch="true">
            <div class="carousel-indicators">
              <button ng-repeat="ticket in ticketsList" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$index}}" ng-class="{'active': $index == 0}" aria-current="true" aria-label="Slide {{$index}}"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item" ng-class="{'active': $index == 0}" ng-repeat="ticket in ticketsList">
                <button ng-click="back()" type="button" class="btn-back-mobile">
                  <i class="bi bi-chevron-left"></i>
                </button>
                <img src="{{eventSelected.poster}}" class="ticket-image-mobile">
                <div class="ticket-info-mobile">
                  <div class="ticket-info-heade-mobile">
                    <div class="row">
                      <div class="col-12" style="text-align: center;">
                        <p class="seus-ingressos-mobile">Seus ingreesos para </p>
                        <p class="ingresso-pendente-mobile">{{eventSelected.title}}</p>
                      </div>
                      <div class="col-12">
                        <div class="date-mobile-pendente">{{convertData(ticket.sessions)}}</div>
                      </div>
                      <div class="row ingressos-text">
                        <div class="col-12">
                          <p class="disponiveis-text-mobile">Ingressos recebidos</p>
                        </div>
                      </div>
                      <div class="row list-tickets-pending" style="height: auto;">
                        <div class="col-5 ticket-pending">
                          <p class="name-enviado-texto">Enviado por</p>
                          <p class="name-enviado">{{ticket.receivedFrom.name}}</p>
                        </div>
                        <div class="col-5 ticket-pending">
                          <p class="name-ticket">{{eventSelected.title}}</p>
                        </div>
                        <div class="col-2">
                          <button type="button" class="btn-bell">
                            <i class="bi bi-bell"></i>
                          </button>
                        </div>
                        <hr class="wallet-divisor">
                        <div class="col-6 ticket-pending">
                          <button type="button" class="btn-recusar" ng-click="setTicketRefuse(ticket)">RECUSAR</button>
                        </div>
                        <div class="col-6 ticket-pending">
                          <button type="button" class="btn-aceitar" ng-click="setTicketAccept(ticket)">ACEITAR</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev" style="display: none;" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" style="display: none;" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        `,
        controller: 'DetailPendingController'
      });
    });

    app.controller('MyTicketsController', ['$scope', '$rootScope', '$http', '$cookies', '$location', '$filter', function TicketsListController($scope, $rootScope, $http,
      $cookies, $location, $filter) {

      $scope.user = angular.fromJson($cookies.get('wp_ing_user'));
      var userId = $scope.user.userId;

      if ($scope.user.document.type == 2) {
        $scope.user.identity.id = $scope.user.document.number;
      } else {
        $scope.user.identity.id = toCpf($scope.user.identity.id);
      }
      
      var apikey = 'tDgFYzwDkGVTxWeAgQxs73Hrs74CaNn2';
      var token = $scope.user.token;

      $scope.prev = false;
      $scope.next = false;

      $scope.convertDataSession = function(session) {
        $scope.dataConvertida = '';
        $scope.convertTime(session.datetime);
        $scope.dataConvertida = $filter('date')(session.datetime, 'EEE dd MMM', 'pt_BR');
        return $scope.dataConvertida;
      }

      $scope.convertData = function(datasPil) {
        $scope.dataConvertida = '';
        if (datasPil.data.length == 1) {
          $scope.convertTime(datasPil.data[0].datetime);
          $scope.dataConvertida = $filter('date')(datasPil.data[0].datetime, 'EEE dd MMM', 'pt_BR');
          return $scope.dataConvertida;

        } else {
          datasPil.data.forEach((element, index) => {
            var hoje = new Date().toDateString();
            var data2 = new Date(datasPil.data[index].datetime).toDateString();

            if (data2 > hoje) {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            } else if (hoje == data2) {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            } else {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            }
          });
          return $scope.dataConvertida;
        }
      }

      $scope.convertTime = function(time) {
        $scope.timeConverted = $filter('date')(time, 'hh:mm', 'pt_BR');
      }

      $scope.getEvents = function(order) {
        $http({
          method: 'GET',
          url: 'https://api.ingresse.com/user/' + userId + '/wallet?apikey=' + apikey +
            '' + order + '&pageSize=12&usertoken=' + token + ''
        }).then(function successCallback(response) {
          $scope.eventsList = response.data.responseData.data;
          if(response.data.responseData.data && response.data.responseData.data.length == 0) {
            $http({
              method: 'GET',
              url: 'https://api.ingresse.com/user/' + userId + '/transfers/?apikey=' + apikey +
                '&status=pending&usertoken=' + token + ''
            }).then(function successCallback(response) {
              $scope.ticketsPending = response.data.responseData;
              if ($scope.ticketsPending.paginationInfo.totalResults > 0 && !window.location.href.includes('no-filter=true')) {
                $location.url(`/pending`);
              }
            }, function errorCallback(response) {
              console.log('Deu erro:', response);
            });
          }
        }, function errorCallback(response) {
          console.log('Deu erro:', response);
        });
      }

      $scope.getEvents('&from=yesterday&order=ASC');

      $scope.previousEvents = function() {
        $scope.prev = true;
        $scope.next = false;
        $scope.getEvents('&to=yesterday&order=DESC');
        $scope.termSearch = 'DESC';
      }

      $scope.nextEvents = function() {
        $scope.next = true;
        $scope.prev = false;
        $scope.getEvents('&from=yesterday&order=ASC');
        $scope.termSearch = 'ASC';
      }

      $scope.detailEvent = function(event, session) {
        $location.url(`/details-event/${event.id}?session=${session.id}`);
      };

      $scope.getTicketsPending = function() {
        $scope.ticketsPendingMessage = '';
        $scope.showPending = false;
        $http({
          method: 'GET',
          url: 'https://api.ingresse.com/user/' + userId + '/transfers/?apikey=' + apikey +
            '&status=pending&usertoken=' + token + ''
        }).then(function successCallback(response) {
          $scope.ticketsPending = response.data.responseData;
          if ($scope.ticketsPending.paginationInfo.totalResults > 0) {
            $scope.showPending = true;
            if ($scope.ticketsPending.paginationInfo.totalResults == 1) {
              $scope.ticketsPendingMessage = 'Você possui ' + $scope.ticketsPending.paginationInfo.totalResults + ' ingresso pendente de aceite';
            } else if ($scope.ticketsPending.paginationInfo.totalResults > 1) {
              $scope.ticketsPendingMessage = 'Você possui ' + $scope.ticketsPending.paginationInfo.totalResults + ' ingressos pendente de aceite';
            }

          }
        }, function errorCallback(response) {
          console.log('Deu erro:', response);
        });
      }

      $scope.getTicketsPending();

      $scope.setEventsPending = function() {
        $location.path('/pending');
      }
    }]);

    app.controller('MyTicketsPendingController', ['$scope', '$rootScope', '$http', '$cookies', '$location', '$filter', function TicketsListController($scope, $rootScope, $http,
      $cookies, $location, $filter) {

      $scope.user = angular.fromJson($cookies.get('wp_ing_user'));
      var userId = $scope.user.userId;
      var apikey = 'tDgFYzwDkGVTxWeAgQxs73Hrs74CaNn2';
      var token = $scope.user.token;

      $scope.backHome = function() {
        $location.path('/?no-filter=true');
      }

      $scope.detailEvent = function(evento) {
        $location.path('/detail-pending/' + evento.event.id);
        $rootScope.eventSelected = {
          event: event.event
        };
      };

      $scope.getEventsPending = function() {
        $scope.ticketsPendingMessage = '';
        $http({
          method: 'GET',
          url: 'https://api.ingresse.com/user/' + userId + '/transfers/?apikey=' + apikey +
            '&status=pending&usertoken=' + token + ''
        }).then(function successCallback(response) {
          $scope.ticketsPending = response.data.responseData;
          $scope.eventsPending = $scope.ticketsPending.data;
          
          if ($scope.ticketsPending.paginationInfo.totalResults > 0) {
            $rootScope.pending = true;
            if ($scope.ticketsPending.paginationInfo.totalResults == 1) {
              $scope.ticketsPendingMessage = 'Você possui ' + $scope.ticketsPending.paginationInfo.totalResults + ' ingresso pendente de aceite';
            } else if ($scope.ticketsPending.paginationInfo.totalResults > 1) {
              $scope.ticketsPendingMessage = 'Você possui ' + $scope.ticketsPending.paginationInfo.totalResults + ' ingressos pendente de aceite';
            }
          }
        }, function errorCallback(response) {
          console.log('Deu erro:', response);
        });
      }

      $scope.getEventsPending();

      $scope.setEventsPending = function() {
        $scope.events = $scope.ticketsPending.data;
        
      }

      $scope.convertDataSession = function(session) {
        scope.dataConvertida = '';
        $scope.convertTime(session.datetime);
        $scope.dataConvertida = $filter('date')(session.datetime, 'EEE dd MMM', 'pt_BR');
        return $scope.dataConvertida;
      }

      $scope.convertData = function(datasPil) {
        $scope.dataConvertida = '';
        if (datasPil.data.length == 1) {
          $scope.convertTime(datasPil.data[0].datetime);
          $scope.dataConvertida = $filter('date')(datasPil.data[0].datetime, 'EEE dd MMM', 'pt_BR');
          return $scope.dataConvertida;

        } else {
          datasPil.data.forEach((element, index) => {
            var hoje = new Date().toDateString();
            var data2 = new Date(datasPil.data[index].datetime).toDateString();

            if (data2 > hoje) {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            } else if (hoje == data2) {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            } else {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            }
          });
          return $scope.dataConvertida;
        }
      }

      $scope.convertTime = function(time) {
        $scope.timeConverted = $filter('date')(time, 'hh:mm', 'pt_BR');
      }
    }]);

    app.controller('DetailController', ['$scope', '$rootScope', '$http', '$cookies', '$location', '$routeParams', '$filter', '$sce', function TicketsListController($scope, $rootScope, $http,
      $cookies, $location, $routeParams, $filter, $sce) {
      $scope.user = angular.fromJson($cookies.get('wp_ing_user'));

      if ($scope.user.document.type == 2) {
        $scope.user.identity.id = $scope.user.document.number;
      } else {
        $scope.user.identity.id = toCpf($scope.user.identity.id);
      }
      
      var userId = $scope.user.userId;
      var apikey = 'tDgFYzwDkGVTxWeAgQxs73Hrs74CaNn2';
      var token = $scope.user.token;

      $scope.convertData = function(datasPil) {
        $scope.dataConvertida = '';
        if (datasPil.data.length == 1) {
          $scope.convertTime(datasPil.data[0].datetime);
          $scope.dataConvertida = $filter('date')(datasPil.data[0].datetime, 'EEE dd MMM', 'pt_BR');
          return $scope.dataConvertida;

        } else {
          datasPil.data.forEach((element, index) => {
            var hoje = new Date().toDateString();
            var data2 = new Date(datasPil.data[index].datetime).toDateString();

            if (data2 > hoje) {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            } else if (hoje == data2) {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            } else {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            }
          });
          return $scope.dataConvertida;
        }
      }

      $scope.isActiveDate = function (date) {
        return date.id == $routeParams.session ? 'active' : ''
      }

      $scope.obterSessoesPorData = function(array, dataEspecifica) {
        let sessoesEncontradas = [];

        array?.forEach(item => {
          if (item.sessions.data && Array.isArray(item.sessions.data)) {
            item.sessions.data.forEach(sessao => {
              if (sessao.datetime) {
                const dataSessao = sessao.datetime.substring(0, 10);
                dataEspecifica = dataEspecifica.substring(0, 10);
                console.log(dataSessao, dataEspecifica)
                if (dataSessao == dataEspecifica) {
                  sessoesEncontradas.push(item);
                }
              }
            });
          }
        });

        return sessoesEncontradas;
      }

      $scope.detailEvent = function(data) {
        let sessaoDoDia = $scope.obterSessoesPorData($scope.tickets, data)
        var elementoSelecionado = jQuery(`.date-event-datail[data-session="${data}"]`);
        if (elementoSelecionado.hasClass('active')) {
          elementoSelecionado.removeClass('active');
          sessaoDoDia = $scope.tickets;
        } else {
          jQuery('.date-event-datail').removeClass('active');
          elementoSelecionado.addClass('active');
          sessaoDoDia = sessaoDoDia;
        }
        
        // $scope.ticketsFilter = $scope.tickets.filter((ticket) => ticket.sessions.data?.[0].id == $routeParams.session)

        $scope.ticketsOnTransfereing = sessaoDoDia.filter((ticket) => ticket.transferedTo?.status === 'pending')
        $scope.ticketsTransfered = sessaoDoDia.filter((ticket) => ticket.transferedTo?.status === 'transfered' || ticket.transferedTo?.status === 'accepted')
        $scope.ticketsReceived = sessaoDoDia.filter((ticket) => ticket.receivedFrom)
        $scope.ticketsAvaliable = sessaoDoDia.filter((ticket) => !ticket.receivedFrom && !ticket.transferedTo && !ticket.isReturn)
        if (jQuery(`.date-event-datail[data-session="${data}"]`).hasClass('active')) {
          sessaoDoDia = $scope.tickets;
        } 
      };

      $scope.convertDataDetail = function(datasPil) {
        $scope.dataConvertida = '';
        var partesDaData = datasPil.dateTime.date.split("/");
        var dia = parseInt(partesDaData[0], 10);
        var mes = parseInt(partesDaData[1], 10) - 1;
        var ano = parseInt(partesDaData[2], 10);

        var data = new Date(ano, mes, dia);
        $scope.dataConvertida = $filter('date')(data, 'EEE dd MMM', 'pt_BR');
        return $scope.dataConvertida;
      }

      $scope.convertStringData = function(date) {
        var data = new Date(date);
        var meses = [
          'jan', 'fev', 'mar', 'abr', 'mai', 'jun',
          'jul', 'ago', 'set', 'out', 'nov', 'dez'
        ];

        var diaDaSemana = data.toLocaleDateString('pt-BR', { weekday: 'short' });

        var dia = data.getDate();

        if (dia < 10)
          dia = '0' + dia

        var mesAbreviado = meses[data.getMonth()];

        var dataConvertida = diaDaSemana + ' ' + dia + ' ' + mesAbreviado;
        return dataConvertida;
      }

      $scope.convertTime = function(time) {
        $scope.timeConverted = $filter('date')(time, 'hh:mm', 'pt_BR');
      }

      $scope.trustHtml = function(text) {
        return $sce.trustAsHtml(text);
      }

      $scope.openMap = function(map) {
        window.open(`https://www.google.com/maps/dir/?api=1&travelmode=driving&layer=traffic&destination=${map.location[0]},${map.location[1]}`);
      }

      $scope.loadEvent = function() {
        $http({
          method: 'GET',
          url: 'https://api.ingresse.com/event/' + $routeParams.id + '?apikey=' + apikey
        }).then(function successCallback(response) {
          $scope.eventSelected = response.data.responseData;
          console.log(response.data.responseData)
          $scope.eventSelected.date = $scope.eventSelected.date
                                        .filter(item => item.status !== "unavailable")
          $scope.eventSelected.date.sort((a, b) => new Date(a.dateTime.date) - new Date(b.dateTime.date))
                                        
          $scope.loadTickets();
          if (jQuery(".accordion-collapse").length === 1) {
            jQuery(".accordion-collapse").addClass('show') 
          }
          
        }, function errorCallback(response) {
          console.log('Deu erro:', response);
        });
      }
      $scope.search = {
        email: ''
      };

      $scope.loadEvent();

      $scope.loadTickets = function() {
        $http({
          method: 'GET',
          url: 'https://api.ingresse.com/user/' + userId + '/tickets?apikey=' + apikey +
            '&eventId=' + $scope.eventSelected.id + '&usertoken=' + token + ''
        }).then(function successCallback(response) {
          $scope.tickets = response.data.responseData.data
            .map((ticket) => {
              if (!ticket.currentHolder) {
                return {...ticket, currentHolder: $scope.user}
              }

              return ticket
            });

          $scope.ticketsSessions = []
          $scope.tickets.forEach(item => {
              if (item.sessions && item.sessions.data) {
                  item.sessions.data.forEach(session => {
                    if (!$scope.ticketsSessions.includes(session.datetime)) {
                      $scope.ticketsSessions.push(session.datetime);
                    }
                  });
              }
          });
          $scope.ticketsSessions.sort();

          console.log($scope.ticketsSessions);
          $scope.tickets.forEach((item) => {console.log(item); console.log(item.sessions)})
          
          $scope.ticketsFilter = $scope.tickets;
          $scope.ticketsOnTransfereing = $scope.ticketsFilter.filter((ticket) => ticket.transferedTo?.status === 'pending')
          $scope.ticketsTransfered = $scope.ticketsFilter.filter((ticket) => ticket.transferedTo?.status === 'transfered' || ticket.transferedTo?.status === 'accepted')
          $scope.ticketsReceived = $scope.ticketsFilter.filter((ticket) => ticket.receivedFrom)
          $scope.ticketsAvaliable = $scope.ticketsFilter.filter((ticket) => !ticket.receivedFrom && !ticket.transferedTo)
          
          console.log('ticketfilter', $scope.ticketsFilter)
          console.log('ticketsOnTransfereing', $scope.ticketsOnTransfereing)
          console.log('ticketsTransfered', $scope.ticketsTransfered)
          console.log('ticketsReceived', $scope.ticketsReceived)
          console.log('ticketsAvaliable', $scope.ticketsAvaliable)

          $scope.ticketsMobile = [...$scope.tickets].reverse()

          document.body.style.overflow = "auto";
        }, function errorCallback(response) {
          console.log('Deu erro:', response);
        });
      }
      $scope.confirmReturnTicket = function() {
        let ticket = $scope.returnTicketItem;
        $http({
          method: 'POST',
          url: 'https://api.ingresse.com/ticket/' + ticket.id + '/transfer?apikey=' + apikey + '&usertoken=' + token,
          headers: {
            accept: 'application/json',
            'content-type': 'application/json'
          },
          data: {
            isReturn: true
          }
        }).then(function successCallback(response) {
          $location.path('/');
          $scope.returnTicket(null);
        }, function errorCallback(response) {
          console.log('Deu errado envio ingresso:', response);
        });
      }
      $scope.returnTicket = function(ticket) {
        $scope.returnTicketItem = ticket;
      
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];

        if(modal.style.display == 'block')
          modal.style.display = "none";
        else
          modal.style.display = "block";
        console.log(modal.style.display)
       
      }

      $scope.back = function() {
        $location.path('/');
      }

      $scope.getTicketsPendingList = function() {
        $http({
          method: 'GET',
          url: 'https://api.ingresse.com/user/' + userId + '/transfers/?apikey=' + apikey +
            '&status=pending&usertoken=' + token + ''
        }).then(function successCallback(response) {
          console.log('Eventos pendentes para pegar ingressos:', response.data.responseData);
        }, function errorCallback(response) {
          console.log('Deu erro para pegar ingressos pendentes:', response);
        });
      }

      $scope.sendToUserWithoutAccount = function(email) {
        console.log()
        console.log('ticket', $scope.ticketUsed)
        console.log('user', $scope.user)
        const formData = new FormData();
        formData.append('user', $scope.search.email);

        $http({
          method: 'POST',
          url: 'https://api.ingresse.com/ticket/' + $scope.ticketUsed.id + '/transfer?apikey=' + apikey + '&usertoken=' + token,
          data: formData,
          headers: {
            'Content-Type': undefined
          }
        }).then(function successCallback(response) {
          console.log('sucesso', response)
          jQuery('#exampleModal').modal('hide');
          jQuery('body').removeClass('modal-open');
          jQuery('.modal-backdrop').remove();
          $scope.loadTickets();
        }, function errorCallback(response) {
          console.log('Deu erro:', response);
        });
      }

      $scope.searchUser = function(form) {
        if (form.$valid) {
          var term = $scope.search.email;
          $http({
            method: 'GET',
            url: 'https://api.ingresse.com/search/transfer/user?size=20&apikey=' + apikey + '&term=' + term + '&usertoken=' + token
          }).then(function successCallback(response) {
            $scope.users = response.data.responseData;
              
            if (!$scope.users.length) {
              jQuery("#user-not-found").removeClass("d-none")
              return
            }

            jQuery("#user-not-found").addClass("d-none")
          }, function errorCallback(response) {
            console.log('Deu erro:', response);
          });
        }
      };

      $scope.setTicket = function(ticket) {
        $scope.users = [];
        $scope.search = {
          email: ''
        };
        $scope.ticketUsed = ticket;
        $scope.ticketSelected = ticket.id;
        jQuery("#user-not-found").addClass("d-none")
        // document.onreadystatechange = function() {
        //   myModal.show();
        // };
      }

      $scope.transferTicket = function(user) {
        $http({
          method: 'POST',
          url: 'https://api.ingresse.com/ticket/' + $scope.ticketSelected + '/transfer?apikey=' + apikey + '&usertoken=' + token,
          headers: {
            accept: 'application/json',
            'content-type': 'application/json'
          },
          data: {
            isReturn: false,
            appRestricted: false,
            user: user.id
          }
        }).then(function successCallback(response) {
          var returnPost = response.data.responseData;

          jQuery('#exampleModal').modal('hide');
          //hide the modal

          jQuery('body').removeClass('modal-open');
          //modal-open class is added on body so it has to be removed

          jQuery('.modal-backdrop').remove();
          //need to remove div with modal-backdrop class

          $scope.loadTickets();
        }, function errorCallback(response) {
          console.log(response);
        });
      }

      $scope.transferCancelTicket = function(ticket) {
        $http({
          method: 'POST',
          url: 'https://api.ingresse.com/ticket/' + ticket.id + '/transfer/' + ticket.transferedTo.transferId + '/?apikey=' + apikey + '&usertoken=' + token,
          headers: {
            accept: 'application/json',
            'content-type': 'application/json'
          },
          data: {
            action: 'cancel',
            transferId: ticket.transferedTo.transferId
          }
        }).then(function successCallback(response) {
          var returnPost = response.data.responseData;
          $scope.loadTickets();
        }, function errorCallback(response) {
          console.log(response);
        });
      }
    }]);

    app.controller('DetailPendingController', ['$scope', '$rootScope', '$http', '$cookies', '$location', '$routeParams', '$filter', function TicketsListController($scope, $rootScope, $http,
      $cookies, $location, $routeParams, $filter) {
      $scope.user = angular.fromJson($cookies.get('wp_ing_user'));
      var userId = $scope.user.userId;
      var apikey = 'tDgFYzwDkGVTxWeAgQxs73Hrs74CaNn2';
      var token = $scope.user.token;

      $scope.loadEvent = function() {
        $http({
          method: 'GET',
          url: 'https://api.ingresse.com/event/' + $routeParams.id + '?apikey=' + apikey
        }).then(function successCallback(response) {
          $scope.eventSelected = response.data.responseData;
          $scope.eventSelected.date = $scope.eventSelected.date
                                        .filter(item => item.status !== "unavailable")
          $scope.eventSelected.date.sort((a, b) => new Date(a.dateTime.date) - new Date(b.dateTime.date))
                                        
          $scope.getTicketsPendingList();
        }, function errorCallback(response) {
          console.log('Deu erro:', response);
        });
      }

      $scope.loadEvent();
      $scope.search = {
        email: ''
      };

      $scope.back = function() {
        $location.path('/pending');
      }

      $scope.getTicketsPendingList = function() {
        $scope.ticketsList = [];
        $http({
          method: 'GET',
          url: 'https://api.ingresse.com/user/' + userId + '/transfers/?apikey=' + apikey +
            '&status=pending&usertoken=' + token + ''
        }).then(function successCallback(response) {
          $scope.ticketsList = response.data.responseData.data;
          $scope.ticketsSessions = []
            response.data.responseData.data.forEach(item => {
              if (item.sessions && item.sessions.data) {
                  item.sessions.data.forEach(session => {
                    if (!$scope.ticketsSessions.includes(session.datetime))
                      $scope.ticketsSessions.push(session.datetime);
                  });
              }
          });
          console.log($scope.ticketsSessions)
        }, function errorCallback(response) {
          console.log('Deu erro para pegar ingressos pendentes:', response);
        });
      }

      $scope.convertStringData = function(date) {
        console.log('date', date)
        var data = new Date(date);
        var meses = [
          'jan', 'fev', 'mar', 'abr', 'mai', 'jun',
          'jul', 'ago', 'set', 'out', 'nov', 'dez'
        ];

        var diaDaSemana = data.toLocaleDateString('pt-BR', { weekday: 'short' });

        var dia = data.getDate();

        if (dia < 10)
          dia = '0' + dia

        var mesAbreviado = meses[data.getMonth()];

        var dataConvertida = diaDaSemana + '\r\n ' + dia + '\r\n ' + mesAbreviado;
        return dataConvertida;
      }

      $scope.convertData = function(datasPil) {
        $scope.dataConvertida = '';
        if (datasPil.data.length == 1) {
          $scope.convertTime(datasPil.data[0].datetime);
          $scope.dataConvertida = $filter('date')(datasPil.data[0].datetime, 'EEE dd MMM', 'pt_BR');
          return $scope.dataConvertida;

        } else {
          datasPil.data.forEach((element, index) => {
            var hoje = new Date().toDateString();
            var data2 = new Date(datasPil.data[index].datetime).toDateString();

            if (data2 > hoje) {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            } else if (hoje == data2) {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            } else {
              $scope.dataConvertida = $filter('date')(datasPil.data[index].datetime, 'EEE dd MMM', 'pt_BR');
              $scope.convertTime(datasPil.data[index].datetime);
            }
          });
          return $scope.dataConvertida;
        }
      }

      $scope.convertTime = function(time) {
        $scope.timeConverted = $filter('date')(time, 'hh:mm', 'pt_BR');
      }



      $scope.setTicketAccept = function(ticketPending) {
        $scope.statusTickect(ticketPending, 'accept');
      }

      $scope.setTicketRefuse = function(ticketPending) {
        $scope.statusTickect(ticketPending, 'refuse');
      }

      $scope.statusTickect = function(ticketPending, actionParam) {
        if (actionParam == 'return') {
          var data = {
            isReturn: true
          }
        } else {
          var data = {
            action: actionParam
          }
        }
        $http({
          method: 'POST',
          url: 'https://api.ingresse.com/ticket/' + ticketPending.ticket.id + '/transfer/' + ticketPending.id + '/?apikey=' + apikey + '&usertoken=' + token,
          headers: {
            accept: 'application/json',
            'content-type': 'application/json'
          },
          data: {
            action: actionParam
          }
        }).then(function successCallback(response) {
          var returnPost = response.data.paginationInfo;
          $location.path('/');
          $scope.getTicketsPendingList();
          console.log('Retorno envio ingresso', returnPost);
        }, function errorCallback(response) {
          console.log(response);
        });
      }
    }]);

    function toCpf(cpf) {
      let formatedCpf = cpf.replace(/\D/g,"")                    //Remove tudo o que não é dígito
      formatedCpf = formatedCpf.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
      formatedCpf = formatedCpf.replace(/(\d{3})(\d)/,"$1.$2")
      return formatedCpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    }
  </script>
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>-child/lib/css/carteira.css">
</head>

<body>
  <div ng-view></div>
  <div class="ings-carteira-infos">
    <article>
      <b>QR CODE</b>
      <p>Os ingressos válidos para verificação na entrada do evento estão disponíveis nos formatos mobile: navegador.</p>

      <b>DUPLICIDADE</b>
      <p>Estes ingressos serão aceitos uma única vez, nossos códigos são únicos e não podem ser duplicados.</p>
    </article>
    <article>
      <b>DOCUMENTAÇÃO</b>

      <p>Para sua segurança, lembre-se de trazer a documentação necessária (RG ou CNH) para comprovar sua identidade.<br>
      No caso de meia entrada, será obrigatório apresentar o documento que comprove o direito ao benefício. Na ausência de comprovante, a organização tem direito de cobrar a diferença do valor inteiro na entrada do evento.</p>
    </article>
  </div>
</body>

</html>