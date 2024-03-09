jQuery(document).ready(() => {
  jQuery("#form-step-1").on("submit", (e) => {
    e.preventDefault();

    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: jQuery("#form-step-1").serialize(),
      success: function (response) {
        jQuery("#button-step-1-next").remove();
        jQuery("#button-step-3-next").removeClass("d-none");
        jQuery("#step-1-container").remove();
        jQuery("#modalFluxoCompra .container, #modalFluxoCompra .row").addClass(
          "ings-modal-full-height"
        );
        jQuery("#modal-body-fluxo-compra")
          .css({ height: "95%" })
          .html(response);
        jQuery("#total").empty();
        jQuery("#button-step-3-next").addClass("d-none");
      },
    });
  });
});

async function handleWindowExit(e) {
  e.preventDefault();
  const message =
    "Ao deixar esta etapa, a reserva será cancelada. Deseja abandonar?";
  e.returnValue = message;

  if (e.stopPropagation) {
    return true;
  }

  const formData = new FormData();
  formData.append("action", "ings_cancel_transaction");
  await fetch(my_ajax_object.ajax_url, {
    method: "POST",
    body: formData,
  });

  return message;
}

function handleModalExitWithCancelTransaction(e) {
  e.preventDefault();

  const confirmacao = confirm(
    "Ao deixar esta etapa, a reserva será cancelada. Deseja abandonar?"
  );
  if (confirmacao) {
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: {
        action: "ings_cancel_transaction",
      },
      success: function (response) {
        location.href = location.href.replace("?ingressos=true", "");
      },
    });
  }
}

function handleModalExit(e) {
  e.preventDefault();
  location.href = location.href.replace("?ingressos=true", "");
}

//
// //VARIÁVEIS
// var urlParams = new URLSearchParams(window.location.search);
// var eventId = urlParams.get('event')
// var apikey = "172f24fd2a903fc0647b61d7112ee1b9814702be"
// var eventInfo
// var eventPassportInfo
// var sessionsInfos
// var initRequests
// var initSteps
// var daysWeeks = [
//   "Domingo",
//   "Segunda",
//   "Terça",
//   "Quarta",
//   "Quinta",
//   "Sexta",
//   "Sábado"
// ]
//
// var observers
// var ticketsList = []
//
// var ticketsNomeados = []
// var ticketSelected
// var searchUserResponse
// var paymentResponse
//
//
// // FUNÇÕES DE INICIALIZAÇÕES
//
//
// jQuery.fn.onClassChange = function (cb, attributes, conditional) {
//   if (conditional) return
//   return jQuery(this).map((_, el) => {
//     return new MutationObserver(mutations => {
//       mutations.forEach(mutation => cb && cb(mutation.target, jQuery(mutation.target).prop(mutation.attributeName)));
//     }).observe(el, {
//       attributes: true,
//       subtree: true,
//       attributeFilter: attributes
//     });
//   });
// }
//
// jQuery('#elementor-popup-modal-79').onClassChange((el, change) => {
//   if (!jQuery('.e-form__indicators__indicator').length) {
//     initSteps = false
//     priceTotal = 0
//     ticketsList = []
//     ticketsNomeados = []
//     jQuery('.total__tickets').hide()
//     return
//   }
//
//   if (!initRequests) {
//     initializeModal()
//     initRequests = true
//   }
//
//   if (jQuery("#tickets-container").children().length == 0 && sessionsInfos) {
//     renderContentModal()
//     jQuery('.e-form__buttons').hide()
//     jQuery('#event-name').text(eventInfo.data.title)
//     jQuery('#event-image').attr('src', eventInfo.data.poster.small)
//   }
//   if (!initSteps) {
//     jQuery('.e-form__indicators__indicator').onClassChange(onChangeStep, ['class', 'style'], initSteps)
//     initSteps = true
//   }
// }, ['class'], initSteps)
//
//
// function onChangeStep(el, change) {
//   jQuery('.e-form__buttons__wrapper__button-previous').hide()
//   switch (jQuery('.e-form__indicators__indicator--state-active').text()) {
//     case 'Ingressos':
//       if (priceTotal) {
//         jQuery('.total__tickets').show()
//         break;
//       }
//
//       jQuery('.total__tickets').hide()
//       break;
//     case 'Identificação':
//       jQuery('.total__tickets').hide()
//
//       if (!Cookies.get('wp_ing_token')) {
//         jQuery('.e-form__buttons__wrapper__button-next').hide()
//       } else {
//         jQuery('.e-form__buttons__wrapper__button-next').show()
//
//       }
//       break;
//     case 'Nomear Ingressos':
//       jQuery('.total__tickets').hide()
//       initializeNomearIngressos()
//       break;
//
//     case 'Pagamento':
//       jQuery('#ings-user-name').text('wislon')
//       break;
//
//     default:
//       return;
//   }
// }
//
// async function initializeModal() {
//   renderTotalTickets([], 0)
//
//   eventInfo = await fetch(`https://event.ingresse.com/public/${eventId}`).then(res => res.json())
//
//   if (!eventInfo.data) return
//
//   jQuery('#event-name').text(eventInfo.data.title)
//   jQuery('#event-image').attr('src', eventInfo.data.poster.small)
//
//   eventPassportInfo = await fetch(`https://api.ingresse.com/event/${eventId}?apikey=${apikey}&fields=customTickets`).then(res => res.json())
//
//   const sessions = [...eventInfo.data.sessions.filter(session => session.status === "available")]
//
//   sessionsInfos = await Promise.all(sessions.map(async session => {
//     const sessionInfo = await fetch(`https://api.ingresse.com/event/${eventId}/session/${session.id}/tickets?apikey=${apikey}`).then(res => res.json())
//     return { sessionId: session.id, ...sessionInfo }
//   }))
//
//   renderContentModal()
//
// }
//
// function initializeNomearIngressos() {
//   if (eventInfo.data.attributes.ticketTransferRequired && ticketsNomeados.length !== ticketsList) {
//     jQuery('.e-form__buttons__wrapper__button-next').addClass('disabled:!bg-[#7A7A7A]')
//   } else {
//     jQuery('.e-form__buttons__wrapper__button-next').removeClass('disabled:!bg-[#7A7A7A]')
//   }
//
//   renderNomearIngressos()
// }
//
//
// function iniatilizePaymentConfirmation() {
//   if (!paymentResponse.responseData) return
//
//   const user = JSON.parse(Cookies.get('wp_ing_user'))
//
//   if (user) {
//     jQuery('#payment-user-email').text(user.email)
//   }
//
//   jQuery('.payment-form').addClass('hidden')
//   jQuery('#payment-close').on('click', () => jQuery('#close-modal').click())
//   jQuery('#continue-buy').on('click', continueBuy)
//
//   renderPaymentDetails()
//   if (paymentResponse.responseData.data.status !== 'success') {
//     paymentWaiting()
//     return
//   }
//
//   paymentSuccess()
// }
//
//
// // FUNÇÕES DE AÇÃO
//
//
// function addTicket(sessionId, ticketId, ticketTypeid, price, name, date) {
//   const session = sessionsInfos.find(sessionInfo => sessionInfo.sessionId === sessionId)
//   const ticket = session.responseData.find(ticket => ticket.id === ticketId)
//   const ticketType = ticket.type.find(element => element.id === ticketTypeid)
//   const count = getCountTickets(sessionId, ticketId, ticketTypeid)
//
//   if (count >= ticketType.restrictions.maximum) return
//
//   ticketsList.push({ sessionId, ticketId, ticketTypeid, price, name, date })
//
//   updateCountTicketsElement(sessionId, ticketId, ticketTypeid)
//   const currentTicketIndex = ticketsList.findIndex(ticket => ticket.sessionId === sessionId && ticket.ticketId === ticketId && ticket.ticketTypeid === ticketTypeid)
//
//   jQuery(`#remove-ticket-${currentTicketIndex}`).removeClass('hidden')
//
//   const newCount = getCountTickets(sessionId, ticketId, ticketTypeid)
//
//   if (newCount >= ticketType.restrictions.maximum) {
//     jQuery(`#add-ticket-${currentTicketIndex}`).addClass('hidden')
//   }
//
// }
//
// function removeTicket(sessionId, ticketId, ticketTypeid) {
//   const index = ticketsList.findIndex(ticket => ticket.sessionId === sessionId && ticket.ticketId === ticketId && ticket.ticketTypeid === ticketTypeid)
//
//   if (index === -1) return
//
//   ticketsList = [...ticketsList.slice(0, index), ...ticketsList.slice(index + 1)]
//
//   updateCountTicketsElement(sessionId, ticketId, ticketTypeid)
//   jQuery(`#add-ticket-${index}`).removeClass('hidden')
//
//
//   const count = getCountTickets(sessionId, ticketId, ticketTypeid)
//   if (!count) {
//     jQuery(`#remove-ticket-${index}`).addClass('hidden')
//
//   }
// }
//
// function updateCountTicketsElement(sessionId, ticketId, ticketTypeid) {
//   const count = getCountTickets(sessionId, ticketId, ticketTypeid)
//   const priceTotal = ticketsList.reduce((carry, sum) => carry + sum.price, 0)
//   Cookies.set('wp_priceTotal', priceTotal)
//   Cookies.set('wp_tickets', JSON.stringify(ticketsList))
//   jQuery(`#count-tickets-${sessionId}-${ticketId}-${ticketTypeid}`).text(count)
//
//   renderTotalTickets(ticketsList, priceTotal)
// }
//
// function getCountTickets(sessionId, ticketId, ticketTypeid) {
//   return ticketsList.filter(ticket => ticket.sessionId === sessionId && ticket.ticketId === ticketId && ticket.ticketTypeid === ticketTypeid).length
// }
//
// function myTicket(index) {
//   const user = JSON.parse(Cookies.get('wp_ing_user'))
//   ticketsNomeados.push({ user, ticket: ticketsList[index], index, transfer: false })
//
//   jQuery(`#user-transfer-email-${index}`).text(user.email)
//
//   jQuery(`#user-transfer-${index}`).addClass('!flex')
//   jQuery(`#choice-user-${index}`).hide()
// }
//
// function changeTicketSelected() {
//   ticketSelected = null
//   jQuery('#stepTransfer').removeClass("!flex")
//   jQuery('#stepTransfer').hide()
//   jQuery('#stepTransferOrMy').show()
// }
//
// async function searchUser() {
//   const text = jQuery("#inputSearchUser").val()
//   const user = JSON.parse(Cookies.get('wp_ing_user'))
//
//   const data = await fetch(`https://api.ingresse.com/search/transfer/user?apikey=${apikey}&term=${text}&size=16&usertoken=${user.token}`).then(res => res.json())
//
//   searchUserResponse = data.responseData
//
//   if (!data.responseData?.length) {
//     jQuery('#notFoundUser').show()
//   } else {
//     jQuery('#notFoundUser').hide()
//     jQuery('#search-user-email').text(jQuery("#inputSearchUser").val())
//     jQuery('#search-user-result').removeClass('hidden')
//     renderSearchUserList(data.responseData)
//   }
// }
//
// function choiceUser(index) {
//   const userTransfer = searchUserResponse[index]
//   jQuery('#notFoundUser').hide()
//   ticketsNomeados.push({ user: userTransfer, ticket: ticketsList[ticketSelected], index: ticketSelected, transfer: true })
//   jQuery('#stepTransfer').removeClass("!flex")
//   jQuery('#stepTransfer').hide()
//   jQuery('#stepTransferOrMy').show()
//   jQuery(`#user-transfer-email-${ticketSelected}`).text(userTransfer.email)
//
//   jQuery(`#user-transfer-${ticketSelected}`).addClass('!flex')
//   jQuery(`#choice-user-${ticketSelected}`).hide()
//   jQuery('#search-user-result').addClass('hidden')
//   ticketSelected = null
// }
//
// function removeUserTransfer(index) {
//   const ticketNomeado = ticketsNomeados?.findIndex(item => item.index === index)
//
//   if (ticketNomeado === -1) return
//
//   ticketsNomeados = [
//     ...ticketsNomeados.slice(0, ticketNomeado),
//     ...ticketsNomeados.slice(ticketNomeado + 1),
//   ]
//
//
//   jQuery(`#user-transfer-${index}`).removeClass('!flex')
//   jQuery(`#choice-user-${index}`).show()
// }
//
// function handleTransferClick(index) {
//   ticketSelected = index
//   jQuery('#stepTransferOrMy').hide()
//   jQuery('#stepTransfer').addClass("!flex")
//   jQuery('#ticketSelectedText').text("Ingresso " + ticketsList[index].name)
//   jQuery('#ticketChangeSelected').on('click', changeTicketSelected)
//   jQuery('#searchUser').on('click', searchUser)
// }
//
// function copyPixCode() {
//   let textArea = document.querySelector('#payment-pix-code');
//   textArea.select();
//   document.execCommand('copy');
// }
//
// function paymentSuccess() {
//   jQuery('#payment-success').removeClass('hidden')
//   jQuery('#payment-details').removeClass('hidden')
//   jQuery('#payment-waiting').addClass('hidden')
//   jQuery('#payment-pix').addClass('hidden')
//   jQuery('#payment-confirmation').removeClass('hidden')
// }
//
// function paymentWaiting() {
//   jQuery('#payment-button-pix-code').on('click', copyPixCode)
//   jQuery('#payment-pix-code').val(paymentResponse.responseData.data.qrcode.url)
//   jQuery('#payment-pix-image').attr('src', paymentResponse.responseData.data.qrcode.image.replace('\\', ''))
//   jQuery('#payment-success').addClass('hidden')
//   jQuery('#payment-details').addClass('hidden')
//   jQuery('#payment-waiting').removeClass('hidden')
//   jQuery('#payment-confirmation').removeClass('hidden')
//   jQuery('#has-payment-pix').on('click', paymentSuccess)
//   jQuery('#show-payment-details').on('click', () => {
//     jQuery('#show-payment-details').addClass('hidden')
//     jQuery('#payment-details').removeClass('hidden')
//   })
// }
//
// function continueBuy() {
//   jQuery('.payment-form').removeClass('hidden')
//   jQuery('#show-payment-details').removeClass('hidden')
//   jQuery('#payment-confirmation').addClass('hidden')
// }
//
//
//
// // FUNÇÕES DE RENDERIZAÇÃO
//
//
//
// function renderTotalTickets(ticketsList, priceTotal) {
//   if (!ticketsList.length) {
//     jQuery('.e-form__buttons').hide()
//     jQuery('.total__tickets').remove()
//     return
//   }
//
//   jQuery('.e-form__buttons').show()
//   jQuery('.total__tickets').remove()
//   jQuery('.e-form__buttons').prepend(`
//                 <div class="total__tickets">
//                     <span class="total__tickets-count">${ticketsList.length} ingressos</span>
//
//                     <span class="total__tickets-value session__title">${priceTotal.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })} (Taxa já inclusa)</span>
//                 </div>
//
//             `)
// }
//
// function renderTickets(event, sessionsInfo) {
//   return event.data.sessions.filter(session => session.status === "available").map(session => {
//
//     const htmlItems = sessionsInfo.find(sessionInfo => sessionInfo.sessionId === session.id).responseData.filter((data) => data.status === "available").map(item => `
//                 <div class="accordion-item">
//                     <h2 class="accordion-header" id="ticketHeader">
//                       <button class="accordion-button ${item.quantityInStock === 0 && 'opacity-50'}" type="button" data-bs-target="#ticket-${item.id}" data-bs-toggle="${item.quantityInStock > 0 && 'collapse'}" aria-expanded="false">
//                         <div class="container flex justify-between items-center">
//                           <span class="row session__title session__ticket">${item.name}</span>
//                           ${item.quantityInStock === 0 ? '<span>Esgotado</span>' : ''}
//                         </div>
//                       </button>
//                     </h2>
//                     <div id="ticket-${item.id}" class="accordion-collapse collapse bg-black" aria-labelledby="ticketHeader" data-bs-parent="#ticket">
//                       <div class="accordion-body container ">
//                     ${item.type.filter(element => element.status === 'available').map((element, elementIndex) => (
//       `<div class="ticket_header--item">
//                               <div class="">
//                                 <span class="session__title">${element.name}</span>
//                                 <div class="">
//                                   <span class="session__title">${element.price.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })}</span><span>(Taxa já inclusa)</span>
//                                 </div>
//                               </div>
//
//                               <div class="">
//                                 <div class="ticket__header--buttons">
//                                   <button type="button" id="remove-ticket-${elementIndex}" onclick="removeTicket(${session.id}, ${item.id}, ${element.id})" class="ticket__header--button hidden">
//                                     <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
//                                       <rect x="0.780273" width="24" height="24" rx="12" fill="white"/>
//                                       <path d="M16.7803 12H8.78027" stroke="#0B0B0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//                                       <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7803 21V21C7.80927 21 3.78027 16.971 3.78027 12V12C3.78027 7.029 7.80927 3 12.7803 3V3C17.7513 3 21.7803 7.029 21.7803 12V12C21.7803 16.971 17.7513 21 12.7803 21Z" stroke="#0B0B0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//                                       </svg>
//                                   </button>
//                                   <span id="count-tickets-${session.id}-${item.id}-${element.id}">0</span>
//                                   <button id="add-ticket-${elementIndex}" type="button" onclick="addTicket(${session.id}, ${item.id}, ${element.id}, ${element.price}, '${item.name} - ${element.name}', '${session.dateTime}')" class="ticket__header--button">
//                                     <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
//                                       <rect x="0.780273" width="24" height="24" rx="12" fill="white"/>
//                                       <path d="M12.7803 8V16" stroke="#0B0B0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//                                       <path d="M16.7803 12H8.78027" stroke="#0B0B0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//                                       <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7803 21V21C7.80927 21 3.78027 16.971 3.78027 12V12C3.78027 7.029 7.80927 3 12.7803 3V3C17.7513 3 21.7803 7.029 21.7803 12V12C21.7803 16.971 17.7513 21 12.7803 21Z" stroke="#0B0B0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//                                     </svg>
//                                   </button>
//                                 </div>
//                               </div>
//                             </div>
//                         `
//     )).join('')}
//                     </div>
//
//                     </div>
//
//               </div>
//             `).join('')
//
//     return `
//             <div class="container form__fields">
//                 <div class="accordion" id="ticket">
//                   <div class="accordion-item">
//                     <h2 class="accordion-header" id="ticketHeader">
//                       <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="false">
//                         <div class="container">
//                           <span class="row session__date">${new Date(session.dateTime).toLocaleString().replace(",", "")}</span>
//                           <span class="row session__title">${daysWeeks[new Date(session.dateTime).getDay()]}</span>
//                         </div>
//                       </button>
//                     </h2>
//                   </div>
//
//                   ${htmlItems}
//
//                 </div>
//               </div>
//           `
//   }).join('')
// }
//
// function renderContentModal() {
//   const html = renderTickets(eventInfo, sessionsInfos)
//   jQuery("#tickets-container").html(html)
// }
//
// function renderNomearIngressos() {
//   const html = ticketsList.map((ticket, index) => {
//     return `
//                     <div class="flex text-white w-full justify-between p-6 border border-[#7A7A7A] rounded-lg items-center h-full">
//                       <div class="flex flex-col gap-2">
//                         <span class="font-semibold text-2xl">${ticket.name.split("-")?.[0]}</span>
//                         <span class="text-normal">${ticket.name.split("-")?.[1]}</span>
//                         <div>
//                           <span class="p-1 bg-[#163592]">${new Date(ticket.date).toLocaleString('pt-BR', { weekday: 'short' }).replace('.', '').toUpperCase()}</span>
//                           <span>${new Date(ticket.date).toLocaleString('pt-BR').replace(',', '')}</span>
//                         </div>
//                       </div>
//
//                       <div id="choice-user-${index}" class="flex flex-col gap-4">
//                         <button type="button" onclick="myTicket(${index})" class="px-10 py-3 rounded-[2.25rem] bg-white !text-[#163592] border-none hover:!bg-[#c36]  hover:!text-white">
//                           MEU INGRESSO
//                         </button>
//                         <button type="button" onclick="handleTransferClick(${index})" class="px-10 py-3 rounded-[2.25rem] text-white bg-[#163592]  border-none">
//                           TRANSFERIR
//                         </button>
//                       </div>
//
//                       <div id="user-transfer-${index}" class="flex flex-col gap-4 h-full hidden">
//                         <span id="user-transfer-email-${index}"></span>
//                         <button onclick="removeUserTransfer(${index})" type="button" class="px-10 py-3 rounded-[2.25rem] bg-white text-[#163592] border-none hover:!bg-[#c36]  hover:text-white">
//                           REMOVER
//                         </button>
//                     </div>
//                   </div>
//                 `
//   }).join('')
//
//   jQuery("#list-tickets").html(html)
// }
//
// function renderSearchUserList(users) {
//   const htmlList = users.map((user, index) => {
//     return `
//           <div class="flex items-center gap-4 bg-[#292929] justify-evenly rounded-lg border border-[#7A7A7A] p-4">
//             <img alt="Imagem do usuário: ${user.name}" src="${user.picture}"  class="!rounded-full !w-16 !h-16" >
//
//             <div class="flex flex-col gap-1 text-white">
//               <span id="search-user-name-0">${user.name}</span>
//               <span id="search-user-email-0">${user.email}</span>
//             </div>
//
//             <div class="cursor-pointer" onclick="choiceUser(${index})">
//               <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
//                 <path d="M13.8333 21.3327L19.1666 15.9993L13.8333 10.666" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
//               </svg>
//             </div>
//           </div>
//
//         `
//   }).join('')
//
//   jQuery('#search-user-list').empty().html(htmlList)
// }
//
// function renderPaymentDetails() {
//
//   let ticketGroup = []
//   const totalValue = ticketsList.reduce((carry, ticket) => carry + ticket.price, 0)
//
//   ticketsList.forEach((ticket) => {
//     const has = ticketGroup.some(item => item.sessionId === ticket.sessionId && item.ticketId === ticket.ticketId && item.ticketTypeid === ticket.ticketTypeid)
//
//     if (has) return
//
//     const count = ticketsList.filter(item => item.sessionId === ticket.sessionId && item.ticketId === ticket.ticketId && item.ticketTypeid === ticket.ticketTypeid).length
//
//     ticketGroup.push({
//       ...ticket,
//       count
//     })
//   })
//
//   const htmlTickets = ticketGroup.map((ticket) => {
//     return `
//             	<div class="flex flex-col text-white font-bold gap-6">
//                     <span>${ticket.name.split("-")?.[0]}</span>
//                     <span class="text-sm">${ticket.name.split("-")?.[1]}</span>
//                     <div class="flex gap-2 items-center">
//                       <span class="p-1 bg-[#163592]">${new Date(ticket.date).toLocaleString('pt-BR', { weekday: 'short' }).replace('.', '').toUpperCase()}</span>
//                       <span>${new Date(ticket.date).toLocaleString('pt-BR').replace(',', '')}</span>
//                     </div>
//                   </div>
//                   <span class="text-white font-bold">${ticket.count}</span>
//                   <div class="flex flex-col text-white font-bold gap-6">
//                     <span class="">${ticket.price.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })}</span>
//                     <span>Taxas inclusas</span>
//                   </div>
//             `
//   }).join('')
//
//   jQuery('#payment-details-tickets').html(htmlTickets)
//   jQuery('#payment-details-count-tickets').text(`${ticketsList.length} ${ticketsList.length === 1 ? 'ingresso' : 'ingressos'}`)
//
//   jQuery('#payment-details-count').text(ticketsList.length)
//
//
//   jQuery('#payment-details-total-tickets').text(totalValue.toLocaleString('pt-br', {
//     style: 'currency',
//     currency: 'BRL'
//   }))
//   jQuery('#payment-details-total-value').text(totalValue.toLocaleString('pt-br', {
//     style: 'currency',
//     currency: 'BRL'
//   }))
// }
