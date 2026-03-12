<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Programa tu traslado</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --brand-primary: #1f4368;
      --brand-soft: #e9edf2;
      --brand-text: #07244a;
    }
    body { background:#f2f3f5; }
    .step { display:none; }
    .step.active { display:block; }
    .small-muted { font-size:0.9rem; color:#6c757d; }
    .search-input { margin-bottom:8px; }
    .invalid-feedback { display:block; }
    .cursor-pointer { cursor:pointer; }
    .tag { background:#eef; padding:3px 8px; border-radius:12px; margin-right:6px; }
    .btn-brasilia {
      background: var(--brand-primary);
      color:#fff;
      border:none;
      border-radius: 32px;
      padding: 10px 24px;
      font-weight: 600;
    }
    .hero-banner {
      position: relative;
      min-height: 270px;
      display: grid;
      place-items: center;
      background: linear-gradient(rgba(28,53,81,.62), rgba(28,53,81,.62)),
        url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat;
      color: #fff;
      letter-spacing: 1px;
    }
    .hero-banner h1 {
      font-size: clamp(2rem, 3.5vw, 3rem);
      font-weight: 700;
      margin: 0;
    }
    #mainContent {
      margin-top: -48px;
      position: relative;
      z-index: 2;
      background: #fff;
      border-radius: 38px 38px 0 0;
      padding: 1.8rem 1.4rem 2rem;
      box-shadow: 0 -6px 24px rgba(0,0,0,0.08);
      min-height: calc(100vh - 210px);
    }
    .title-ppal {
      color: var(--brand-text);
      font-size: clamp(1.8rem, 3vw, 2.8rem);
      font-weight: 700;
      margin-bottom: 0;
    }
    .search-card {
      border: 0;
      box-shadow: none;
      background: transparent;
    }
    .title-step {
      border: 0;
      background: var(--brand-soft);
      color: #52637a;
      border-radius: 24px;
      padding: .5rem .9rem;
      font-size: .92rem;
      font-weight: 600;
      white-space: nowrap;
    }
    .title-step.active {
      background: #d3dfec;
      color: var(--brand-primary);
    }
    .form-control, .form-select {
      border-radius: 20px;
      min-height: 46px;
      background-color: #eff2f5;
      border: 1px solid #eff2f5;
    }
    .form-control:focus, .form-select:focus {
      box-shadow: 0 0 0 .2rem rgba(31,67,104,.15);
      border-color: rgba(31,67,104,.25);
      background-color: #fff;
    }
    @media (max-width: 768px) {
      #pills-tab {
        overflow-x: auto;
        flex-wrap: nowrap;
        padding-bottom: .4rem;
      }
    }
  </style>
  <link rel="stylesheet" href="style.css">
</head>
<body>


<header class="hero-banner">
  <h1>UNITRANSCO</h1>
</header>

<div class="container my-4" id="mainContent">
  <h2 class="title-ppal">Bienvenidos,</h2>
  <p class="small-muted">Te conectamos con la región caribe.</p>
  <div id="alertPlaceholder"></div>

  <div class="card search-card">
    <div class="card-body">
      <!-- Stepper -->
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="" role="presentation">
          <button class="title-step active" id="step-1-tab" data-step="1" type="button">1. Tiquete</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="title-step" id="step-2-tab" data-step="2" type="button">2. Servicio</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="title-step" id="step-3-tab" data-step="3" type="button">3. Detalle Ruta</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="title-step" id="step-4-tab" data-step="4" type="button">4. Pasajeros & Pago</button>
        </li>
      </ul>

      <!-- STEPS -->
      <form id="transferForm" novalidate>
        <!-- STEP 1 -->
        <div class="step active" data-step="1">
          <h5>1. Ingresar información del Tiquete</h5>
          <div class="row">
            
            <div class="col-md-6 mb-3">
              <label class="form-label">Número de documento</label>
              <input type="text" id="idNumber" class="form-control" placeholder="Número de documento">
              <div class="invalid-feedback d-none" id="idNumberFeedback">Ingrese un número válido .</div>
            </div>
          </div>
          <div class="mb-3">
            <button type="button" class="btn btn-brasilia" id="btnCheckTickets">Confirmar tiquetes</button>            
            <div class="mt-3" id="ticketsResult"></div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-brasilia" id="toStep2">Siguiente</button>
          </div>
        </div>

        <!-- STEP 2 -->
        <div class="step" data-step="2">
          <h5>2. Seleccionar servicio y cantidad</h5>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Tiquete a usar</label>
              <select id="ticketSelect" class="form-select"></select>
              <div class="small-muted mt-1" id="ticketInfo"></div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Tipo de servicio</label>
              <select id="serviceType" class="form-select">
                <option value="">-- Seleccione --</option>
                <option value="fixed">Ruta fija</option>
                <option value="custom">Ruta personalizada</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Opción traslado</label>
              <select id="transferOption" class="form-select">
                <option value="">-- Seleccione --</option>
                <option value="to_boarding">Hacia el punto de abordaje</option>
                <option value="from_descent">Desde el punto de descenso</option>                
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Cantidad de pasajeros</label>
              <input type="number" id="passengerCount" class="form-control" min="1" value="1">
            </div>
            
          </div>
          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" id="backTo1">Atrás</button>
            <button type="button" class="btn btn-brasilia" id="toStep3">Siguiente</button>
          </div>
        </div>

        <!-- STEP 3 -->
        <div class="step" data-step="3">
          <h5>3. Indicar ruta, paradas y horarios</h5>

          <div id="fixedRouteSection" class="mb-4 d-none">
            <h6>Ruta fija</h6>
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="fixed_to_boarding_check">
                  <label class="form-check-label" for="fixed_to_boarding_check">Traslado hacia el punto de abordaje</label>
                </div>
                <div class="mb-2 d-none" id="fixed_to_boarding">
                  <label class="form-label">Lugar de recogida (paradas)</label>
                  <select id="stopToSelect" class="form-select"></select>
                  <label class="form-label mt-2">Hora de recogida</label>
                  <select id="timeToSelect" class="form-select"></select>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="fixed_from_descent_check">
                  <label class="form-check-label" for="fixed_from_descent_check">Traslado desde punto de descenso</label>
                </div>
                <div class="mb-2 d-none" id="fixed_from_descent">
                  <label class="form-label">Destino final (paradas)</label>
                  <select id="stopFromSelect" class="form-select"></select>
                  <label class="form-label mt-2">Hora de abordaje</label>
                  <select id="timeFromSelect" class="form-select"></select>
                </div>
              </div>
            </div>
          </div>

          <div id="customRouteSection" class="mb-4 d-none">
            <h6>Ruta personalizada</h6>
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="custom_to_boarding_check">
                  <label class="form-check-label" for="custom_to_boarding_check">Traslado hacia el punto de abordaje</label>
                </div>
                <div class="mb-2 d-none" id="custom_to_boarding">
                  <label class="form-label">Barrio Origen</label>
                  <select id="barrioToSelect" class="form-select"></select>
                  <label class="form-label mt-2">Dirección</label>
                  <input id="addressTo" class="form-control" placeholder="Calle 123 #45-67">
                  <label class="form-label mt-2">Hora de recogida</label>
                  <input id="timeToCustom" type="time" class="form-control">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="custom_from_descent_check">
                  <label class="form-check-label" for="custom_from_descent_check">Traslado desde punto de descenso</label>
                </div>
                <div class="mb-2 d-none" id="custom_from_descent">
                  <label class="form-label">Barrio destino</label>                  
                  <select id="barrioFromSelect" class="form-select"></select>
                  <label class="form-label mt-2">Dirección</label>
                  <input id="addressFrom" class="form-control" placeholder="Calle 1 #2-3">
                  <label class="form-label mt-2">Hora de abordaje</label>
                  <input id="timeFromCustom" type="time" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-9 mb-3 align-self-end">
              <div id="vehicleOptions" class="small-muted"></div>
            </div>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" id="backTo2">Atrás</button>
            <button type="button" class="btn btn-brasilia" id="toStep4">Siguiente</button>
          </div>
        </div>

        <!-- STEP 4 -->
        <div class="step" data-step="4">
          <h5>4. Registrar información de pasajeros y confirmar</h5>

          <div id="passengersContainer"></div>
          <hr>

          <!-- ========== NUEVA SECCION: Registro de Titular de la Factura (2.1.8) ========== -->
          <div id="invoiceHolderSection" class="mb-4">
            <h6>Datos del titular de la factura</h6>
            <div class="mb-2 small-muted">¿El titular de la factura es alguno de los pasajeros?</div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="invoice_is_passenger" id="invoiceIsPassenger_yes" value="yes" checked>
              <label class="form-check-label" for="invoiceIsPassenger_yes">Sí, es un pasajero</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="radio" name="invoice_is_passenger" id="invoiceIsPassenger_no" value="no">
              <label class="form-check-label" for="invoiceIsPassenger_no">No, es una persona diferente</label>
            </div>

            <!-- Si es pasajero: select para elegir cuál -->
            <div id="invoiceSelectPassenger" class="mb-3">
              <label class="form-label">Selecciona el pasajero titular</label>
              <select id="invoicePassengerSelect" class="form-select"></select>
            </div>

            <!-- Si NO es pasajero: formulario para titular -->
            <div id="invoiceHolderForm" class="border p-3 d-none">
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label class="form-label">Tipo de identificación</label>
                  <select id="invoice_id_type" class="form-select">
                    <option value="">-- Seleccione --</option>
                    <option>Registro civil</option>
                    <option>Tarjeta de Identidad</option>
                    <option>Cédula de ciudadanía</option>
                    <option>Tarjeta de extranjería</option>
                    <option>Cédula de extranjería</option>
                    <option>NIT</option>
                    <option>Pasaporte</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="form-label">Número de identificación</label>
                  <input id="invoice_id_number" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                  <label class="form-label">Dig</label>
                  <input id="invoice_id_dig" class="form-control" readonly>
                </div>
                <!-- Persona jurídica (NIT) -->
                <div id="nitFields" class="w-100 d-none">
                  <div class="col-md-12 mb-2 mt-2">
                    <label class="form-label">Razón social</label>
                    <input id="invoice_razon_social" class="form-control">
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Correo electrónico</label>
                    <input id="invoice_email_nit" class="form-control" placeholder="empresa@dominio.com">
                  </div>
                </div>
                <!-- Persona natural -->
                <div id="naturalFields" class="w-100 d-none">
                  <div class="col-md-6 mb-2 mt-2">
                    <label class="form-label">Primer nombre</label>
                    <input id="invoice_first_name" class="form-control">
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Segundo nombre</label>
                    <input id="invoice_second_name" class="form-control">
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Primer apellido</label>
                    <input id="invoice_first_lastname" class="form-control">
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Segundo apellido</label>
                    <input id="invoice_second_lastname" class="form-control">
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Correo electrónico</label>
                    <input id="invoice_email_natural" class="form-control" placeholder="nombre@dominio.com">
                  </div>
                </div>
              </div>
            </div>
            <!-- /NUEVA SECCION -->
          </div>

          <div id="costSummary" class="mb-3"></div>


          <!-- PASO 10 Metodo de pago -->
  
        <div class="step-content flex-grow-1">
            <h5>Metodo de pago</h5>
            <p class="text-muted">Serás redirigido a PayU para completar tu pago de forma segura.</p>

            <!-- Botón visual -->
            <div class="text-center mt-3">
                <img src="https://prod-developers.s3.amazonaws.com/latam/images/BlancoVerde/Medios_Pago_Blanco_Verde_125x125.jpg"
                    title="PayU - Medios de pago" alt="PayU - Medios de pago" style="width: 100%;" />
            </div>

            
        </div>
        
  

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" id="backTo3">Atrás</button>
            <button type="submit" class="btn btn-brasilia" id="btnConfirm">Confirmar y pagar</button>
          </div>
        </div>
      </form>

      <!-- Formulario oculto PayU -->
            <form id="payuForm" method="post" action="https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/">
                <input name="merchantId" type="hidden" value="508029">
                <input name="accountId" type="hidden" value="512321">
                <input name="description" type="hidden" value="Compra de traslados">
                <input name="referenceCode" type="hidden" id="payuReference" value="">
                <input name="amount" type="hidden" id="payuAmount" value="10000.00">
                <input name="tax" type="hidden" id="payuTax" value="0">
                <input name="taxReturnBase" type="hidden" value="0">
                <input name="currency" type="hidden" value="COP">
                <input name="signature" type="hidden" id="payuSignature" value="">
                <input name="test" type="hidden" value="1"> <!-- ⚠️ 0 en producción -->
                <input name="buyerEmail" type="hidden" id="payuBuyerEmail" value="">
                <input name="responseUrl" type="hidden"
                    value="https://txtest.lappiz.io/ExpresoBrasilia_Lappiz.api/api/functions/RespuestaPayuTraslado">
                <input name="confirmationUrl" type="hidden"
                    value="https://txtest.lappiz.io/ExpresoBrasilia_Lappiz.api/api/functions/confirmPay">
            </form>
    </div>
  </div>
</div>



<div id="resumenPasajerosScreen" class="container my-4 d-none">
  <h2>Resumen de Pasajeros</h2>
  <div id="resumenPasajerosContainer" class="row gy-3"></div>
  <button id="btnVolver" class="btn btn-primary mt-4">Volver</button>
</div>


<!-- jQuery + Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js"></script>


<script>
$('<script>', {
    src: 'https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js',
    type: 'text/javascript'
}).appendTo('head');
let tiquetesEncontrados = false;
const LAPPIZ_BASE_URL = "https://txtest.lappiz.io/ExpresoBrasilia_Lappiz.api/api";
async function getLappizToken() {
  const tokenKey = "lappiz_token";
  const tokenExpiryKey = "lappiz_token_expiry";

  const savedToken = localStorage.getItem(tokenKey);
  const expiry = localStorage.getItem(tokenExpiryKey);

  // Si el token existe y no ha expirado, lo reutiliza
  if (savedToken && expiry && new Date() < new Date(expiry)) {
    return savedToken;
  }

  // Si no hay token o venció, solicitar uno nuevo
  const res = await fetch(`${LAPPIZ_BASE_URL}/functions/Token`, {
    method: "POST"
  });

  if (!res.ok) throw new Error("Error al obtener el token");

  const token = await res.text(); // El endpoint devuelve el token como texto plano

  // Guardar token y tiempo de expiración (ej. 55 minutos)
  localStorage.setItem(tokenKey, token);
  localStorage.setItem(tokenExpiryKey, new Date(Date.now() + 55 * 60 * 1000).toISOString());

  return token;
}
async function executeQueryLappiz(query) {
  try {
    const token = await getLappizToken(); // obtiene o renueva el token

    const myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Authorization", `Bearer ${token}`);

    const body = JSON.stringify({
      query: query,
      tenantId: "null",
      parameters: {
        aType: "execTx",
        environment: "TEST",
        userId: "3ef31612-dafa-431d-8dd9-55db175eb422"
      }
    });

    const response = await fetch(`${LAPPIZ_BASE_URL}/lappiz/sp/query`, {
      method: "POST",
      headers: myHeaders,
      body,
      redirect: "follow"
    });

    if (!response.ok) throw new Error("Error en la ejecución de la query");

    const result = await response.text(); // o .json() si esperas JSON   
    return result;
  } catch (err) {
    console.error("❌ Error ejecutando la query:", err);
    throw err;
  }
}

const ID_TYPES = [
  "Cédula de ciudadanía", "Tarjeta de identidad", "Registro civil", "Pasaporte",
  "Cédula de extranjería", "Carnet diplomático", "Acta de nacimiento",
  "Cédula de identidad", "Permiso especial de permanencia (PEP)",
  "Permiso por protección temporal (PPT)", "Salvoconducto", "Tarjeta Andina",
  "Documento extranjero"
];

const COUNTRY_CODES = [
  { code: "+57", name: "Colombia" }  
];

const VEHICLES_BY_CITY = [];

/* --------------------------- Helpers UI --------------------------- */
function showAlert(msg, type='info') {
  const iconMap = {
    info: 'info',
    warning: 'warning',
    danger: 'error',
    success: 'success'
  };
  
  const icon = iconMap[type] || 'info';

  Swal.fire({
    icon: icon,
    title: msg,
    confirmButtonText: 'Aceptar',
    // Puedes agregar más opciones si quieres
  });
}



function toDateOnSameDay(ticketDateISO, timeHHmm) {
  const date = new Date(ticketDateISO);
  const [h,m] = timeHHmm.split(':').map(Number);
  const combined = new Date(date.getFullYear(), date.getMonth(), date.getDate(), h, m, 0);
  return combined;
}

/* --------------------------- Init --------------------------- */
function populateIdTypes() {
  const $sel = $('#idTypeSelect');
  $sel.empty();
  $sel.append(`<option value="">-- Seleccione --</option>`);
  ID_TYPES.forEach(t => $sel.append(`<option>${t}</option>`));
}


/* --------------------------- Stepper helpers --------------------------- */
function goToStep(n) {
  $('.step').removeClass('active');
  $(`.step[data-step="${n}"]`).addClass('active');
  $('.title-step').removeClass('active');
  $(`.title-step[data-step="${n}"]`).addClass('active');
}

(function () {
    const LOADER_ID = "vb-global-loader";
    const STYLE_ID = "vb-global-loader-style";
    const HIDDEN_CL = "vb-hidden";

    // Inyectar estilos
    if (!document.getElementById(STYLE_ID)) {
        const css = `
      #${LOADER_ID} {
        position: fixed;
        top: 12px;
        right: 12px;
        z-index: 2147483647;
        background: rgba(255,255,255,0.95);
        border: 1px solid rgba(0,0,0,0.08);
        padding: 8px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        font-size: 0.95rem;
        color: #333;
        gap: 8px;
      }
      #${LOADER_ID} .vb-loader-text { white-space: nowrap; }
      .${HIDDEN_CL} { display: none !important; }

      @media (max-width: 420px) {
        #${LOADER_ID} { font-size: .85rem; padding: 6px 8px; top: 8px; right: 8px; }
        #${LOADER_ID} .spinner-border { width: 1rem; height: 1rem; }
      }
    `;
        const style = document.createElement("style");
        style.id = STYLE_ID;
        style.appendChild(document.createTextNode(css));
        document.head.appendChild(style);
    }

    // Crear loader
    let loader = document.getElementById(LOADER_ID);
    if (!loader) {
        loader = document.createElement("div");
        loader.id = LOADER_ID;
        loader.className = HIDDEN_CL;
        loader.innerHTML = `
      <div class="spinner-border spinner-border-sm text-primary"></div>
      <div class="vb-loader-text">Procesando…</div>
    `;
        document.body.appendChild(loader);
    }

    // Control interno
    let pending = 0;
    let showTimer = null;
    const SHOW_DELAY_MS = 120;

    function scheduleShow() {
        pending++;
        if (loader.classList.contains(HIDDEN_CL) && !showTimer) {
            showTimer = setTimeout(() => {
                loader.classList.remove(HIDDEN_CL);
                showTimer = null;
            }, SHOW_DELAY_MS);
        }
    }

    function scheduleHide() {
        pending = Math.max(0, pending - 1);
        if (pending > 0) return;

        if (showTimer) {
            clearTimeout(showTimer);
            showTimer = null;
            loader.classList.add(HIDDEN_CL);
            return;
        }

        loader.classList.add(HIDDEN_CL);
    }

    // Exponer funciones manuales
    window.VBLoader = {
        show: scheduleShow,
        hide: scheduleHide
    };

    /***** Interceptar fetch *****/
    if (window.fetch) {
        const _fetch = window.fetch.bind(window);
        window.fetch = async function (input, init) {
            scheduleShow();
            try {
                return await _fetch(input, init);
            } catch (err) {
                throw err;
            } finally {
                scheduleHide();
            }
        };
    }

    /***** Interceptar jQuery AJAX *****/
    if (window.jQuery) {
        jQuery(document).ajaxStart(() => scheduleShow());
        jQuery(document).ajaxStop(() => scheduleHide());
    }

    /***** Interceptar XMLHttpRequest *****/
    (function () {
        const _send = window.XMLHttpRequest?.prototype?.send;
        if (!_send) return;

        window.XMLHttpRequest.prototype.send = function () {
            scheduleShow();
            this.addEventListener("loadend", () => scheduleHide(), { once: true });
            return _send.apply(this, arguments);
        };
    })();
})();



/* --------------------------- Document ready --------------------------- */
$(document).ready(function(){
  populateIdTypes();  

  // nav buttons (click on pills)
 $('.title-step').on('click', function(){
  const step = $(this).data('step');

  // Validar que no se pueda ir a pasos mayores sin completar previos
  if (step === 2 && !tiquetesEncontrados) {
    showAlert('Debes confirmar tiquetes antes de continuar.', 'warning');
    return;
  }

  if (step === 3) {
    // Validar que paso 2 esté completo
    if (!$('#ticketSelect').val() || !$('#serviceType').val() || !$('#transferOption').val()) {
      showAlert('Completa la información del paso 2 antes de continuar.', 'warning');
      return;
    }
  }

  if (step === 4) {
    // Validar que paso 3 esté completo
    const service = $('#serviceType').val();
    let validStep3 = true;
    if(service === 'fixed') {
      if($('#fixed_to_boarding_check').is(':checked')) {
        if(!$('#stopToSelect').val() || !$('#timeToSelect').val()) validStep3 = false;
      }
      if($('#fixed_from_descent_check').is(':checked')) {
        if(!$('#stopFromSelect').val() || !$('#timeFromSelect').val()) validStep3 = false;
      }
    } else if(service === 'custom') {
      if($('#custom_to_boarding_check').is(':checked')) {
        if(!$('#barrioToSelect').val() || !$('#addressTo').val() || !$('#timeToCustom').val()) validStep3 = false;
      }
      if($('#custom_from_descent_check').is(':checked')) {
        if(!$('#barrioFromSelect').val() || !$('#addressFrom').val() || !$('#timeFromCustom').val()) validStep3 = false;
      }
    }
    if (!validStep3) {
      showAlert('Completa la información del paso 3 antes de continuar.', 'warning');
      return;
    }
  }

  goToStep(step);
});

function updateStepButtons() {
  // Siempre habilitar paso 1
  $('.title-step').prop('disabled', true);
  $(`.title-step[data-step="1"]`).prop('disabled', false);

  if (tiquetesEncontrados) {
    $(`.title-step[data-step="2"]`).prop('disabled', false);
  }
  if ($('#ticketSelect').val() && $('#serviceType').val() && $('#transferOption').val()) {
    $(`.title-step[data-step="3"]`).prop('disabled', false);
  }
  // Para paso 4, validar paso 3 completado similar a arriba
  // ...
}

// Llamar updateStepButtons() cada vez que cambie un campo relevante
$('#idNumber, #ticketSelect, #serviceType, #transferOption').on('change keyup', updateStepButtons);



  // Step 1 -> 2
 $('#toStep2').on('click', function(){
  const idNum = $('#idNumber').val().trim();

  if(!idNum || !/^[A-Za-z0-9\-]+$/.test(idNum)) {
    $('#idNumberFeedback').removeClass('d-none');
    return;
  } else {
    $('#idNumberFeedback').addClass('d-none');
  }

  if (!tiquetesEncontrados) {
    showAlert('No se encontraron tiquetes asociados a ese número. No puedes continuar.', 'warning');
    return;
  }
  
  goToStep(2);
});


  $('#backTo1').on('click', function(){ goToStep(1); });

 
});

 // Consulta servicios/tiquetes con Axios
 async function consultarServiciosConAxios(numeroDocumento) {
  const endpoint = `https://txtest.lappiz.io/ExpresoBrasilia_Lappiz.api/api/functions/getTiquetes?numero=${encodeURIComponent(numeroDocumento)}`;
  const response = await axios.get(endpoint, {
    headers: {
      Accept: 'application/json'
    },
    timeout: 20000
  });

  return response.data;
 }

 $('#btnCheckTickets').on('click', async function () {
   VBLoader.show();

  const numero = $('#idNumber').val().trim();

  if (!numero) {
     VBLoader.hide();
    showAlert('Debes ingresar número de documento.', 'warning');
    return;
  }

  $('#ticketsResult').html('<div class="small text-muted">Consultando servicios...</div>');

  try {
    const response = await consultarServiciosConAxios(numero);
    VBLoader.hide();
    console.log('✅ Respuesta Axios:', response);

    if (response.error || !response.data || !Array.isArray(response.data) || response.data.length === 0) {
      $('#ticketsResult').html('<div class="alert alert-warning">No se encontraron servicios/tiquetes asociados a ese número.</div>');
      $('#ticketSelect').empty();
      tiquetesEncontrados = false;
      return;
    }

    tiquetesEncontrados = true;
    const tiquetes = response.data;
    let html = `<div class="list-group">`;
    const $sel = $('#ticketSelect').empty();
    $sel.append(`<option value="">-- Seleccione el tiquete --</option>`);

    tiquetes.forEach(t => {
      let origen = '';
      let destino = '';

      if (t.descripcion && t.descripcion.includes('-') && t.descripcion.includes('/')) {
        const partes = t.descripcion.split('-');
        partes.shift();
        const trayecto = partes.join('-').trim();
        const [ori, des] = trayecto.split('/');
        origen = (ori || '').trim();
        destino = (des || '').trim();
      }

      $sel.append(`
        <option value="${t.numero}"
          data-fecha="${t.fechaViaje || ''}"
          data-descripcion="${t.descripcion || ''}"
          data-cliente="${t.cliente || ''}"
          data-origin="${origen}"
          data-destination="${destino}">
          ${t.fechaViaje || '-'} — ${origen} / ${destino}
        </option>
      `);

      html += `
        <div class="list-group-item">
          <strong>${t.descripcion || '-'}</strong><br>
          <div>🕓 Fecha de viaje: <strong>${t.fechaViaje || '-'}</strong></div>
          <div>👤 Pasajero: ${t.cliente || '-'}</div>
          <div>📍 Origen: ${origen || '-'} — Destino: ${destino || '-'}</div>
          <div class="small text-muted">Empresa: ${t.empresa || '-'} — Agencia: ${t.agencia || '-'}</div>
        </div>
      `;
    });

    html += `</div>`;
    $('#ticketsResult').html(html);
  } catch (error) {
    VBLoader.hide();
    console.error('❌ Error al consultar los servicios con Axios:', error);
    const message = error?.response?.data?.message || 'Error al consultar los servicios. Verifica los datos o inténtalo más tarde.';
    $('#ticketsResult').html(`<div class="alert alert-danger">${message}</div>`);
  }
});

  // Cuando seleccionan ticket
$('#ticketSelect').on('change', function(){
  const opt = $(this).find('option:selected');
  if (!opt.val()) { 
    $('#ticketInfo').text(''); 
    return; 
  }

  const origin = opt.data('origin');
  const dest = opt.data('destination');
  const fecha = opt.data('fecha');

  $('#ticketInfo').text(`Origen: ${origin} — Destino: ${dest} — Salida: ${fecha}`);
  localStorage.setItem('Origen', origin);
  localStorage.setItem('Destino', dest);
  localStorage.setItem('Salida', fecha);
  // Llenar dependientes 
  populateVehicles(origin, dest);
});

$('#serviceType').on('change', function () {
  const serviceType = $(this).val(); // obtiene el valor actual del select
  localStorage.setItem('serviceType', serviceType); 
});

$('#transferOption').on('change', function () {
  const transferOption = $(this).val();

  const origin = localStorage.getItem('Origen');
  const dest = localStorage.getItem('Destino');
  const fecha = localStorage.getItem('Salida');
  const serviceType = localStorage.getItem('serviceType');

  // Determinar el tipo de parada (1, 2 o 3) según serviceType
  let stopType;
  switch (transferOption) {
    case 'to_boarding':
      stopType = 1;
      break;
    case 'from_descent':
      stopType = 2;
      break;
    default:
      stopType = 3;
  }

  // Ejecutar funciones según el tipo de servicio
  if (serviceType === 'fixed') {
    populateStops(origin, dest, stopType);
    //populateVehicles(origin, dest);
  } else if (serviceType === 'custom') {
    populateBarrios(origin, dest,stopType);
  }

  const option = $(this).val();
 

  // Limpiamos todo primero
  $('#fixed_to_boarding_check, #fixed_from_descent_check, #custom_to_boarding_check, #custom_from_descent_check')
    .prop('checked', false)
    .prop('disabled', false);

  $('#fixed_to_boarding, #fixed_from_descent, #custom_to_boarding, #custom_from_descent')
    .addClass('d-none');

  if (!option) return; // Si no se seleccionó nada, no hacemos nada

  // Ruta fija
  if (serviceType === 'fixed') {
    if (option === 'to_boarding') {
      $('#fixed_to_boarding_check').prop('checked', true);
      $('#fixed_from_descent_check').prop('disabled', true);
      $('#fixed_to_boarding').removeClass('d-none');
    }
    if (option === 'from_descent') {
      $('#fixed_from_descent_check').prop('checked', true);
      $('#fixed_to_boarding_check').prop('disabled', true);
      $('#fixed_from_descent').removeClass('d-none');
    }
  }

  // Ruta personalizada
  if (serviceType === 'custom') {
    if (option === 'to_boarding') {
      $('#custom_to_boarding_check').prop('checked', true);
      $('#custom_from_descent_check').prop('disabled', true);
      $('#custom_to_boarding').removeClass('d-none');
    }
    if (option === 'from_descent') {
      $('#custom_from_descent_check').prop('checked', true);
      $('#custom_to_boarding_check').prop('disabled', true);
      $('#custom_from_descent').removeClass('d-none');
    }
  }

});

  // Service type change
  $('#serviceType').on('change', function(){
    const v = $(this).val();
    if(v === 'fixed') {
      $('#fixedRouteSection').removeClass('d-none'); $('#customRouteSection').addClass('d-none');
    } else if(v === 'custom') {
      $('#customRouteSection').removeClass('d-none'); $('#fixedRouteSection').addClass('d-none');
    } else { $('#fixedRouteSection').addClass('d-none'); $('#customRouteSection').addClass('d-none'); }
  });

  // toggle fixed/custom sub-sections
  $('#fixed_to_boarding_check').on('change', function(){ $('#fixed_to_boarding').toggleClass('d-none', !this.checked); });
  $('#fixed_from_descent_check').on('change', function(){ $('#fixed_from_descent').toggleClass('d-none', !this.checked); });
  $('#custom_to_boarding_check').on('change', function(){ $('#custom_to_boarding').toggleClass('d-none', !this.checked); });
  $('#custom_from_descent_check').on('change', function(){ $('#custom_from_descent').toggleClass('d-none', !this.checked); });

  // filter selects
  $('#searchStopsTo').on('input', function(){ filterSelectOptions('#stopToSelect', $(this).val()); });
  $('#searchStopsFrom').on('input', function(){ filterSelectOptions('#stopFromSelect', $(this).val()); });
  $('#searchBarriosTo').on('input', function(){ filterSelectOptions('#barrioToSelect', $(this).val()); });
  $('#searchBarriosFrom').on('input', function(){ filterSelectOptions('#barrioFromSelect', $(this).val()); });

  // stop select changes to populate times
  $('#stopToSelect').on('change', function(){
    const stopId = $(this).val();
    const ruta = $(this).find('option:selected').data('ruta'); 
    let query = `
    SELECT 
     hrf.*
    FROM ExpresoBrasilia_Lappiz_HorariosRutaFija hrf
    LEFT JOIN ExpresoBrasilia_Lappiz_RutaFija rf ON rf.Id = hrf.RutaFK
    WHERE rf.Id = '${ruta}'
  `;

  executeQueryLappiz(query)
    .then(res => {
      let allStops = [];

      // 👇 Primero, aseguramos que 'res' sea un array real (no texto)
      if (typeof res === "string") {
        try {
          res = JSON.parse(res);
        } catch (e) {
          console.error("❌ No se pudo parsear la respuesta:", e);
        }
      }

      // 👇 Verificamos estructura y aplanamos correctamente
      if (Array.isArray(res)) {
        res.forEach(sub => {
          if (Array.isArray(sub)) {
            sub.forEach(item => allStops.push(item));
          }
        });
      }

      // 👇 Eliminamos duplicados por CEHora
      const uniqueStops = [];
      const seenHours = new Set();

      allStops.forEach(stop => {
        if (stop.CEHora && !seenHours.has(stop.CEHora)) {
          seenHours.add(stop.CEHora);
          uniqueStops.push(stop);
        }
      });

      // 👇 Verificamos en consola
      console.log("🕒 Horarios únicos:", uniqueStops);

      // 👇 Poblar el select
      const $selectHoras = $("#timeToSelect");
      $selectHoras.empty().append(`<option value="">Seleccione una hora</option>`);

      uniqueStops.forEach(stop => {
        $selectHoras.append(
          `<option value="${stop.Id}" data-ruta="${stop.RutaFK}" data-vehiculo="${stop.Vehiculo}" data-hora="${stop.CEHora}">${stop.CEHora}</option>`
        );
      });


      if (uniqueStops.length === 0) {
        $selectHoras.append('<option disabled>Sin horarios disponibles</option>');
      }
    })
    .catch(err => console.error("⚠️ Error ejecutando query:", err));

  });


  $('#stopFromSelect').on('change', function(){

    const stopId = $(this).val();
    const ruta = $(this).find('option:selected').data('ruta'); 
    let query = `
    SELECT 
     hrf.*
    FROM ExpresoBrasilia_Lappiz_HorariosRutaFija hrf
    LEFT JOIN ExpresoBrasilia_Lappiz_RutaFija rf ON rf.Id = hrf.RutaFK
    WHERE rf.Id = '${ruta}'
  `;

  executeQueryLappiz(query)
  .then(res => {
    let allStops = [];

    // 👇 Primero, aseguramos que 'res' sea un array real (no texto)
    if (typeof res === "string") {
      try {
        res = JSON.parse(res);
      } catch (e) {
        console.error("❌ No se pudo parsear la respuesta:", e);
      }
    }

    // 👇 Verificamos estructura y aplanamos correctamente
    if (Array.isArray(res)) {
      res.forEach(sub => {
        if (Array.isArray(sub)) {
          sub.forEach(item => allStops.push(item));
        }
      });
    }

    // 👇 Eliminamos duplicados por CEHora
    const uniqueStops = [];
    const seenHours = new Set();

    allStops.forEach(stop => {
      if (stop.CEHora && !seenHours.has(stop.CEHora)) {
        seenHours.add(stop.CEHora);
        uniqueStops.push(stop);
      }
    });

    // 👇 Verificamos en consola
    console.log("🕒 Horarios únicos:", uniqueStops);

    // 👇 Poblar el select
    const $selectHoras = $("#timeFromSelect");
    $selectHoras.empty().append(`<option value="">Seleccione una hora</option>`);

    uniqueStops.forEach(stop => {
      $selectHoras.append(
        `<option value="${stop.Id}" data-ruta="${stop.RutaFK}" data-vehiculo="${stop.Vehiculo}" data-hora="${stop.CEHora}">${stop.CEHora}</option>`
      );
    });


    if (uniqueStops.length === 0) {
      $selectHoras.append('<option disabled>Sin horarios disponibles</option>');
    }
  })
  .catch(err => console.error("⚠️ Error ejecutando query:", err));

    
    //populateTimesFrom(stop.times, arrivalISO, '#timeFromSelect');
  });


  // Detectar cambios en los selects o inputs de hora
$('#barrioToSelect, #barrioFromSelect, #timeToCustom, #timeFromCustom').on('change', function() {
  // 🔹 Detectamos cuál campo cambió
  const id = $(this).attr('id');
  console.log('🟡 Cambió:', id);

  let selectedOption, hora, punto, valor, ruta;

  // === Si cambió un select ===
  if (id === 'barrioToSelect' || id === 'barrioFromSelect') {
    selectedOption = $(this).find(':selected');
    punto = selectedOption.text() || '';
    valor = parseFloat(selectedOption.data('precio')) || 0;

    // Tomar la hora del input correspondiente
    if (id === 'barrioToSelect') {
      hora = $('#timeToCustom').val() || '';
      ruta = 'Destino'; // etiqueta que tú quieras usar
    } else {
      hora = $('#timeFromCustom').val() || '';
      ruta = 'Origen';
    }

  // === Si cambió una hora ===
  } else if (id === 'timeToCustom' || id === 'timeFromCustom') {
    hora = $(this).val() || '';

    if (id === 'timeToCustom') {
      selectedOption = $('#barrioToSelect').find(':selected');
      ruta = 'Destino';
    } else {
      selectedOption = $('#barrioFromSelect').find(':selected');
      ruta = 'Origen';
    }

    punto = selectedOption.text() || '';
    valor = parseFloat(selectedOption.data('precio')) || 0;
  }

  // Validamos que haya información suficiente
  if (!valor || !punto || !hora) {
    console.log('⚠️ Falta información para calcular (hora/punto/valor).');
    return;
  }

  // 🔹 Llamamos la función principal
  calculateTotal(hora, ruta, valor, punto);
});



function calculateTotal(hora,ruta,valor,punto){

  const pax = parseInt($('#passengerCount').val()) || 1;
  const basePerPax = valor;
  const subtotal = basePerPax * pax;
  //const serviceFee = Math.round(subtotal * 0.05); // 5% servicio
  const serviceFee=0;
  const taxes = Math.round(subtotal * 0.19);      // 19% IVA
  const total = subtotal + serviceFee + taxes;

  console.log(`🚌 Punto: ${punto}, Hora: ${hora}, Ruta: ${ruta}`);
  console.log(`💰 Base: ${basePerPax}, Subtotal: ${subtotal}, Total: ${total}`);

  // 🔹 Renderizamos el resumen
  $('#costSummary').html(`
    <h6>Resumen de costos</h6>
    <div><strong>Punto:</strong> ${punto}</div>
    <div><strong>Hora:</strong> ${hora}</div>
    <div><strong>Ruta:</strong> ${ruta}</div>
    <div>Tarifa base por pasajero: <strong>${basePerPax.toLocaleString('es-CO')}</strong></div>
    <div>Subtotal (${pax} pax): <strong>${subtotal.toLocaleString('es-CO')}</strong></div>    
    <div>Impuestos (19%): <strong>${taxes.toLocaleString('es-CO')}</strong></div>
    <hr>
    <div><strong>Total a pagar: ${total.toLocaleString('es-CO')}</strong></div>
  `);

  // 🔹 Guardamos los datos en el formulario para uso posterior
  $('#transferForm').data('cost', { basePerPax, subtotal, serviceFee, taxes, total, hora, ruta, punto });
}
  // toStep3 validation
  $('#toStep3').on('click', function(){
    const ticket = $('#ticketSelect').val();
    const service = $('#serviceType').val();
    const option = $('#transferOption').val();
    const pax = parseInt($('#passengerCount').val()) || 0;
    if(!ticket) { showAlert('Selecciona el tiquete que usarás.', 'warning'); return; }
    if(!service) { showAlert('Selecciona el tipo de servicio.', 'warning'); return; }
    if(!option) { showAlert('Selecciona la opción de traslado.', 'warning'); return; }
    if(pax < 1) { showAlert('Ingresa la cantidad de pasajeros.', 'warning'); return; }
    goToStep(3);
  });

  // toStep4 -> prepara pasajeros, resumen, y la sección de titular de factura
  $('#toStep4').on('click', function(){
    const service = $('#serviceType').val();
    const option = $('#transferOption').val();
    // Validaciones por tipo (fixed/custom)
    if(service === 'fixed') {
      if($('#fixed_to_boarding_check').is(':checked')) {
        if(!$('#stopToSelect').val() || !$('#timeToSelect').val()) { showAlert('Completa lugar/hora de recogida (ruta fija).', 'warning'); return; }
      }
      if($('#fixed_from_descent_check').is(':checked')) {
        if(!$('#stopFromSelect').val() || !$('#timeFromSelect').val()) { showAlert('Completa destino/hora de abordaje (ruta fija).', 'warning'); return; }
      }
    }
    if(service === 'custom') {
      if($('#custom_to_boarding_check').is(':checked')) {
        if(!$('#barrioToSelect').val() || !$('#addressTo').val() || !$('#timeToCustom').val()) { showAlert('Completa barrio/dirección/hora de recogida (ruta personalizada).', 'warning'); return; }
      }
      if($('#custom_from_descent_check').is(':checked')) {
        if(!$('#barrioFromSelect').val() || !$('#addressFrom').val() || !$('#timeFromCustom').val()) { showAlert('Completa barrio/dirección/hora de abordaje (ruta personalizada).', 'warning'); return; }
      }
    }

    buildPassengersForm();
    // poblamos el select de pasajeros para seleccionar titular (si aplica)
    populateInvoicePassengerSelect();
    // reset sección titular
    resetInvoiceSection();
    //calculateAndShowCost();
    
     handleCalculation();


    goToStep(4);
  });


  function handleCalculation() {
    let selectedTime, selectedStop;

    // 🔹 Detectamos cuál bloque está visible (ida o regreso)
    if ($('#timeFromSelect').is(':visible') && $('#stopFromSelect').is(':visible')) {
      selectedTime = $('#timeFromSelect').find(':selected');
      selectedStop = $('#stopFromSelect').find(':selected');
    } else if ($('#timeToSelect').is(':visible') && $('#stopToSelect').is(':visible')) {
      selectedTime = $('#timeToSelect').find(':selected');
      selectedStop = $('#stopToSelect').find(':selected');
    } else {
      console.warn("⚠️ Ningún bloque visible para calcular.");
      return;
    }

    // 🔹 Capturamos los valores de los data attributes
    const hora = selectedTime.data('hora') || '';
    const ruta = selectedTime.data('ruta') || '';
    const valor = parseFloat(selectedStop.data('valor')) || 0;
    const punto = selectedStop.data('punto') || '';

    // 🔹 Llamamos a tu función de cálculo
    calculateTotal(hora, ruta, valor, punto);
}

// 🔹 Escuchamos cambios en ambos selects
$('#timeFromSelect, #stopFromSelect, #timeToSelect, #stopToSelect').on('change', handleCalculation);


  // Back navigation
  $('#backTo2').on('click', function(){ goToStep(2); });
  $('#backTo3').on('click', function(){ goToStep(3); });

  // Manejo del radio: titular es pasajero?
  $('input[name="invoice_is_passenger"]').on('change', function(){
    const v = $('input[name="invoice_is_passenger"]:checked').val();
    toggleInvoiceSection(v === 'yes');
  });

  // Si el usuario cambia tipo de identificación en formulario de titular
  $('#invoice_id_type').on('change', function(){
    const t = $(this).val();
    if(t === 'NIT') {
      $('#nitFields').removeClass('d-none');
      $('#naturalFields').addClass('d-none');
    } else {
      $('#nitFields').addClass('d-none');
      $('#naturalFields').removeClass('d-none');
    }
  });

  // autollenar Dig al escribir número (simple: copia el mismo valor)
  $('#invoice_id_number').on('input', function(){
    $('#invoice_id_dig').val($(this).val());
  });

  // Submit final del formulario
  $('#transferForm').on('submit',async  function(e){
    e.preventDefault();
    console.log('asdsadasdasd');

    // Validar pasajeros
    const totalPax = parseInt($('#passengerCount').val());
    let valid = true;
    const passengers = [];
    for(let i=1;i<=totalPax;i++){
      const name = $(`#pax_name_${i}`).val() || '';
      const last = $(`#pax_last_${i}`).val() || '';
      const idType = $(`#pax_idtype_${i}`).val() || '';
      const idNum = $(`#pax_idnumber_${i}`).val() || '';
      const email = $(`#pax_email_${i}`).val() || '';
      const country = $(`#pax_country_${i}`).val() || '';
      const phone = $(`#pax_phone_${i}`).val() || '';
      if(!name.match(/^[A-Za-z\s]+$/)) { showAlert(`Nombre del pasajero ${i} inválido. Solo letras y espacios.`, 'danger'); valid=false; break; }
      if(!last.match(/^[A-Za-z\s]+$/)) { showAlert(`Apellido del pasajero ${i} inválido.`, 'danger'); valid=false; break; }
      if(!idType) { showAlert(`Tipo de documento pasajero ${i} requerido.`, 'danger'); valid=false; break; }
      if(!idNum) { showAlert(`Número de documento pasajero ${i} requerido.`, 'danger'); valid=false; break; }
      if(!validateEmail(email)) { showAlert(`Email pasajero ${i} inválido.`, 'danger'); valid=false; break; }
      if(!/^\d{10}$/.test(phone)) { showAlert(`Celular pasajero ${i} inválido. Debe ser 10 dígitos.`, 'danger'); valid=false; break; }
      passengers.push({ name, last, idType, idNum, email, country, phone });
    }
    if(!valid) return;

    // Validar titular de la factura (2.1.8)
    const invoiceIsPassenger = $('input[name="invoice_is_passenger"]:checked').val() === 'yes';
    let invoiceHolder = null;
    if(invoiceIsPassenger) {
      const chosen = $('#invoicePassengerSelect').val();
      if(!chosen) { showAlert('Selecciona cuál pasajero es el titular de la factura.', 'warning'); return; }
      // chosen tendrá formato "index|nombre completo" -> extraer index
      const idx = parseInt(chosen.split('|')[0], 10);
      if(isNaN(idx) || idx < 1 || idx > passengers.length) { showAlert('Selección de titular inválida.', 'danger'); return; }
      invoiceHolder = Object.assign({}, passengers[idx-1]); // toma datos del pasajero seleccionado
    } else {
      // Validar formulario del titular NO pasajero
      const idType = $('#invoice_id_type').val();
      const idNumber = $('#invoice_id_number').val().trim();
      const dig = $('#invoice_id_dig').val().trim();
      if(!idType) { showAlert('Tipo de identificación titular requerido.', 'warning'); return; }
      if(!idNumber) { showAlert('Número de identificación titular requerido.', 'warning'); return; }
      if(idType === 'NIT') {
        const razon = $('#invoice_razon_social').val().trim();
        const email = $('#invoice_email_nit').val().trim();
        if(!razon) { showAlert('Razón social requerida para NIT.', 'warning'); return; }
        if(!validateEmail(email)) { showAlert('Email titular (NIT) inválido.', 'warning'); return; }
        invoiceHolder = {
          idType, idNumber, dig, razonSocial: razon, email
        };
      } else {
        // persona natural
        const first = $('#invoice_first_name').val().trim();
        const second = $('#invoice_second_name').val().trim();
        const last1 = $('#invoice_first_lastname').val().trim();
        const last2 = $('#invoice_second_lastname').val().trim();
        const email = $('#invoice_email_natural').val().trim();
        if(!first) { showAlert('Primer nombre titular requerido.', 'warning'); return; }
        if(!last1) { showAlert('Primer apellido titular requerido.', 'warning'); return; }
        if(!validateEmail(email)) { showAlert('Email titular inválido.', 'warning'); return; }
        invoiceHolder = {
          idType, idNumber, dig, first, second, last1, last2, email
        };
      }
    }

    // Si todo OK, construir payload y enviar (simulado)
    const payload = buildPayload(passengers);
    payload.invoiceHolder = invoiceHolder;
    console.log('Payload final (enviar al backend):', payload);

    
    // 🚨 Estos valores deberían venir calculados antes (del resumen)
    const total = normalizeAmount(payload.cost.total || 10000);
    const email = payload.invoiceHolder?.email || "test@test.com";
    const reference = "ORD-" + Date.now();
    let nameFunction = "generateSignature";
    let lappizFunctionId = "d5856670-bd74-49f0-8ef8-786580c6aefe";
    let method = "POST";
    let body = {
      referenceCode: reference,
      amount: total,
      currency: "COP",
      data: payload,
    };
    let config = { nameFunction, lappizFunctionId, body, method };
    const { signature } = await execLF(config);
    console.log('signature');
    console.log(signature);
    // Rellenar formulario
    document.getElementById("payuReference").value = reference;
    document.getElementById("payuAmount").value = total;
    document.getElementById("payuTax").value = payload.cost.total;
    document.getElementById("payuBuyerEmail").value = email;
    document.getElementById("payuSignature").value = signature;

    payload.total=total;
    payload.reference=reference;
    payload.total=total;
    payload.signature=signature;

    payload.transferOption=$('#transferOption').val();
    payload.ticketSelect=$('#ticketSelect').val();

    if(serviceType === 'fixed') {
      if(transferOption === 'to_boarding') {
        payload.stopToSelect = $('#stopToSelect').val();
        payload.timeToSelect = $('#timeToSelect').val();
      } else if(transferOption === 'from_descent') {
        payload.stopFromSelect = $('#stopFromSelect').val();
        payload.timeFromSelect = $('#timeFromSelect').val();
      }
    } else if(serviceType === 'custom') {
      if(transferOption === 'to_boarding') {
        payload.barrioToSelect = $('#barrioToSelect').val();
        payload.addressTo = $('#addressTo').val();
        payload.timeToCustom = $('#timeToCustom').val();
      } else if(transferOption === 'from_descent') {
        payload.barrioFromSelect = $('#barrioFromSelect').val();
        payload.addressFrom = $('#addressFrom').val();
        payload.timeFromCustom = $('#timeFromCustom').val();
      }
    }


    

    guardarpasajeros(payload);



    // Enviar a PayU
    document.getElementById("payuForm").submit();
 

}); // end document.ready

async function guardarpasajeros(payload) {
  try {
    const {
      ticketId,
      serviceType,
      transferOption,
      passengers,
      routeDetail,
      cost,
      invoiceHolder,
      reference // Usamos este campo para identificar los inserts
    } = payload;


    // Insertar pasajeros
    for (const pax of passengers) {
      let barrio, address, pickupTime, punto, hora, ruta,stop;

      if (serviceType === 'fixed') {
        if (transferOption === 'to_boarding') {
          barrio = routeDetail.toBoarding?.barrio || '';
          address = routeDetail.toBoarding?.address || '';
          pickupTime = routeDetail.toBoarding?.boardingTime || '';
          stop=routeDetail.toBoarding?.stopId || '';
          punto = cost.punto;
          hora = cost.hora;
          ruta = cost.ruta;
        } else if (transferOption === 'from_descent') {
          barrio = routeDetail.fromDescent?.barrio || '';
          address = routeDetail.fromDescent?.address || '';
          pickupTime = routeDetail.fromDescent?.boardingTime || '';
          stop=routeDetail.toBoarding?.stopId || '';
          punto = cost.punto;
          hora = cost.hora;
          ruta = cost.ruta;
        }
      } else if (serviceType === 'custom') {
        if (transferOption === 'to_boarding') {
          barrio = routeDetail.toBoarding?.barrio || '';
          address = routeDetail.toBoarding?.address || '';
          pickupTime = routeDetail.toBoarding?.pickupTime || '';
          stop=routeDetail.toBoarding?.stopId || '';
          punto = cost.punto;
          hora = cost.hora;
          ruta = cost.ruta;
        } else if (transferOption === 'from_descent') {
          barrio = routeDetail.fromDescent?.barrio || '';
          address = routeDetail.fromDescent?.address || '';
          pickupTime = routeDetail.fromDescent?.pickupTime || '';
          stop=routeDetail.toBoarding?.stopId || '';
          punto = cost.punto;
          hora = cost.hora;
          ruta = cost.ruta;
        }
      }

      

      const infotraslado = JSON.stringify({
        barrio,
        address,
        pickupTime,
        punto,
        hora,
        ruta,
        transferOption,
        serviceType,
        ticketId,
        routeDetail,
        cost,
        invoiceHolder

      }).replace(/'/g, "''");

      const query = `
        INSERT INTO ExpresoBrasilia_Lappiz_Traslados
          (CENombre, Apellidos, Nmerodocumento, Correoelectronico, Celular, Infotraslado, Reference)
        VALUES
          (
            '${pax.name.replace(/'/g, "''")}',
            '${pax.last.replace(/'/g, "''")}',
            '${pax.idNum.replace(/'/g, "''")}',
            '${pax.email.replace(/'/g, "''")}',
            '${pax.phone.replace(/'/g, "''")}',
            '${infotraslado}',
            '${reference}'
          )
      `;
      await executeQueryLappiz(query);
      console.log('Guardado pasajero:', pax.name);
    }

    console.log('Todos los pasajeros guardados.');

    // Ahora consultamos los pasajeros insertados con esa referencia
    const querySelect = `
      SELECT * FROM ExpresoBrasilia_Lappiz_Traslados
      WHERE Reference = '${reference}'
    `;

    
    const resultadosRaw = await executeQueryLappiz(querySelect);
    let resultados;

    // Parsear resultados si vienen en texto JSON
      try {
        resultados = typeof resultadosRaw === 'string' ? JSON.parse(resultadosRaw) : resultadosRaw;
      } catch {
        resultados = resultadosRaw;
      }

      console.log('Pasajeros insertados recuperados:', resultados);

      mostrarResumenPasajeros(resultados);

      return resultados;
   
  } catch (err) {
    console.error('Error en guardarpasajeros:', err);
    throw err;
  }
}


function mostrarResumenPasajeros(raw) {
  /* ---------- 1) Parseo seguro ---------- */
  if (typeof raw === 'string') {
    try { raw = JSON.parse(raw); } catch(e) { console.error('No se pudo parsear', e); return; }
  }

  /* ---------- 2) Aplanar manualmente ---------- */
  const flat = [];
  if (Array.isArray(raw)) {
    raw.forEach(item => {
      if (Array.isArray(item))  flat.push(...item);
      else if (item && typeof item === 'object') flat.push(item);
    });
  }

  /* ---------- 3) Eliminar duplicados por documento ---------- */
  const vistos = new Set();
  const pasajeros = flat.filter(p => {
    const key = p.Nmerodocumento || p.Id;
    if (!key || vistos.has(key)) return false;
    vistos.add(key);
    return true;
  });

  /* ---------- 4) Mostrar u ocultar pantallas ---------- */
  $('#mainContent').hide();
  $('#resumenPasajerosScreen').removeClass('d-none');

  /* ---------- 5) Render tarjetas ---------- */
  const $c = $('#resumenPasajerosContainer').empty();

  pasajeros.forEach((pax, idx) => {
    // Parsear JSON de Infotraslado (puede fallar si viene vacío)
    let traslado = {};
    try { traslado = JSON.parse(pax.Infotraslado || '{}'); } catch{}

    const html = `
      <div class="col-md-4">
        <div class="card h-100 p-3">
          <h5>Pasajero ${idx + 1}</h5>
          <p><strong>Nombre:</strong> ${pax.CENombre} ${pax.Apellidos}</p>
          <p><strong>Documento:</strong> ${pax.Nmerodocumento}</p>
          <p><strong>Email:</strong> ${pax.Correoelectronico}</p>
          <p><strong>Teléfono:</strong> ${pax.Celular}</p>
          <p><strong>Punto de abordaje:</strong> ${traslado.punto || '-'}</p>
          <p><strong>Hora:</strong> ${traslado.hora || '-'}</p>
          <div id="qr_${idx}" style="width:150px;height:150px;margin:auto;"></div>
        </div>
      </div>`;
    $c.append(html);
    const datatraslado = JSON.parse(pax.Infotraslado);
    /* ---------- 6) Generar código QR ---------- */
    
    const qrData = JSON.stringify({
      nombre: pax.CENombre,
      apellidos: pax.Apellidos,
      doc: pax.Nmerodocumento,
      ref: pax.Reference      
    });
    new QRCode(document.getElementById(`qr_${idx}`), { text: qrData, width:150, height:150 });
  });

  /* ---------- 7) Botón Volver ---------- */
  $('#btnVolver').off('click').on('click', () => {
    $('#resumenPasajerosScreen').addClass('d-none');
    $('#mainContent').show();
  });
}



/* --------------------------- POBLAR SELECTS (stops, barrios, vehicles) --------------------------- */
function populateStops(originCity, destCity,option=0) {
  let query = "";
  console.log('opcion:'+option)

  if(option==1){
    // Asegúrate de validar la entrada:
  const saforiginCity = originCity.replace(/'/g, "''"); // Escapa comillas simples
  const cityBeforeDash = saforiginCity.split('-')[0].trim();

  query = `
    SELECT 
      rf.Id AS ruta_id,
      rf.CERuta AS ruta,
      rf.CiudadOrigenRuta AS ciudad_ruta_id,
      pa.Id AS parada_id,
      pa.CEParada AS parada,
      pa.Valor,
      pab.CENombre AS puntoabordaje
    FROM ExpresoBrasilia_Lappiz_ParadasRutaFija pa
    LEFT JOIN ExpresoBrasilia_Lappiz_RutaFija rf ON rf.Id = pa.RutaForenKey
    INNER JOIN ExpresoBrasilia_Lappiz_PuntosAbordaje pab ON pab.Id = rf.PuntoDescenso
    WHERE pab.CENombre = '${cityBeforeDash}'
  `;
  }

  if (option == 2) {
  // Asegúrate de validar la entrada:
    const safeCity = destCity.replace(/'/g, "''"); // Escapa comillas simples
    const cityBeforeDashdest = safeCity.split('-')[0].trim();

    query = `
      SELECT 
        rf.Id AS ruta_id,
        rf.CERuta AS ruta,
        rf.CiudadOrigenRuta AS ciudad_ruta_id,
        pa.Id AS parada_id,
        pa.CEParada AS parada,
        pa.Valor,
        pab.CENombre AS puntoabordaje
      FROM ExpresoBrasilia_Lappiz_ParadasRutaFija pa
      LEFT JOIN ExpresoBrasilia_Lappiz_RutaFija rf ON rf.Id = pa.RutaForenKey
      INNER JOIN ExpresoBrasilia_Lappiz_PuntosAbordaje pab ON pab.Id = rf.Puntoabordaje
      WHERE pab.CENombre = '${cityBeforeDashdest}'
    `;
  }
executeQueryLappiz(query)
  .then(res => {
    console.log("✔️ Respuesta original:", res);

    // 🧩 Si la respuesta viene como string JSON, la convertimos
    if (typeof res === "string") {
      try {
        res = JSON.parse(res);
      } catch (e) {
        console.error("⚠️ No se pudo parsear JSON:", e, res);
        return;
      }
    }

    // 🧩 Si viene dentro de una propiedad 'data', la usamos
    if (res && res.data) {
      res = res.data;
    }

    // 🧩 Validar que haya datos tipo array
    if (!Array.isArray(res) || res.length === 0) {
      console.error("⚠️ Estructura inesperada en la respuesta:", res);
      return;
    }

    // 🧩 Aplanar (funciona tanto si es [[...]] como si es [...])
    const allStops = res.flatMap(r => (Array.isArray(r) ? r : [r]))
                        .filter(s => s && s.parada_id && s.parada);

    if (allStops.length === 0) {
      console.error("⚠️ No se encontraron paradas válidas:", res);
      return;
    }

    // 🔹 Eliminar duplicados
    const uniqueStops = allStops.filter(
      (stop, index, self) =>
        index === self.findIndex(s => s.parada_id === stop.parada_id)
    );

    // 🔹 Ordenar por nombre
    uniqueStops.sort((a, b) => a.parada.localeCompare(b.parada));

    // 🔹 Poblar selects
    const $stopFrom = $('#stopFromSelect')
      .empty()
      .append('<option value="">-- Seleccione --</option>');

    const $stopTo = $('#stopToSelect')
      .empty()
      .append('<option value="">-- Seleccione --</option>');

    uniqueStops.forEach(s => {
      const option = `<option value="${s.parada_id}" data-ruta="${s.ruta_id}" data-valor="${s.Valor ?? ''}" 
      data-punto="${s.puntoabordaje ?? ''}">${s.parada}</option>`;
      $stopFrom.append(option);
      $stopTo.append(option);
    });

    console.log(`✅ ${uniqueStops.length} paradas únicas cargadas`);
  })
  .catch(err => console.error("⚠️ Error ejecutando query:", err));



}

// 🔹 Escuchar el cambio de hora en cualquiera de los dos selects
$('#timeFromSelect, #timeToSelect').on('change', function () {
  const selectedOption = $(this).find(':selected');
  if (!selectedOption.val()) return;

  const vehiculo = selectedOption.data('vehiculo'); // Id del vehículo (puede venir vacío)
  const query = `
    SELECT *
    FROM ExpresoBrasilia_Lappiz_getionvehiculos 
    WHERE Id = '${vehiculo}'
  `;

  executeQueryLappiz(query)
    .then(res => {
      console.log("✔️ Respuesta raw:", res);

      // --- 1) Asegurar que 'res' sea un objeto/array parseado ---
      if (typeof res === 'string') {
        try {
          res = JSON.parse(res);
        } catch (e) {
          console.error("❌ No se pudo parsear res como JSON:", e, res);
          // abortamos porque no tenemos datos válidos
          $('#vehicleOptions').text('Error al obtener vehículos (respuesta inválida).');
          return;
        }
      }

      // Si la respuesta viene dentro de una propiedad (por ejemplo { data: [...] }) tomarla
      if (res && typeof res === 'object' && !Array.isArray(res)) {
        // buscar la primera propiedad que sea array
        const arrProp = Object.values(res).find(v => Array.isArray(v));
        if (arrProp) res = arrProp;
      }

      // --- 2) Aplanar manualmente (soporta [[...],[...]] o [{...}] o mezcla) ---
      const flat = [];
      if (Array.isArray(res)) {
        res.forEach(item => {
          if (Array.isArray(item)) {
            item.forEach(inner => flat.push(inner));
          } else if (item && typeof item === 'object') {
            flat.push(item);
          }
        });
      } else {
        console.warn("⚠️ Respuesta no es array después de parseo:", res);
      }

      console.log("✔️ Aplanado:", flat);

      // --- 3) Eliminar duplicados por Id ---
      const seen = new Set();
      const vehicles = [];
      flat.forEach(v => {
        if (!v || !v.Id) return;
        if (!seen.has(v.Id)) {
          seen.add(v.Id);
          vehicles.push({
            id: v.Id,
            placa: v.CEPlaca || v.Placa || '',
            estado: v.Estado || '',
            capacity: v.NumeroPasajeros ?? v.Capacity ?? 0
          });
        }
      });

      console.log("🚗 Vehículos únicos:", vehicles);

      // --- 4) Renderizar en #vehicleOptions ---
      const $div = $('#vehicleOptions').empty();
      if (vehicles.length === 0) {
        $div.text('No hay vehículos configurados en la ciudad.');
        return;
      }

      vehicles.forEach(v => {
        // puedes personalizar la etiqueta HTML
        $div.append(
          `<span class="tag me-1 mb-1 d-inline-block p-1 border rounded">
             Placa Vehiculo:${v.placa || v.id} — Capacidad: ${v.capacity} Pasajeros             
           </span>`
        );
      });
    })
    .catch(err => {
      console.error("⚠️ Error ejecutando query:", err);
      $('#vehicleOptions').text('Error al consultar vehículos.');
    });
});




function populateBarrios(originCity, destCity,option=0) {
  let query = "";
  console.log('opcion:'+originCity)
  console.log('des:'+destCity)

  if(option==1){
    // Asegúrate de validar la entrada:
  const saforiginCity = originCity.replace(/'/g, "''"); // Escapa comillas simples
  const cityBeforeDash = saforiginCity.split('-')[0].trim();

  query = `
    SELECT 
      rp.*,
      l.Id as localidad_id,
      l.CENombre as localidad
    FROM ExpresoBrasilia_Lappiz_GestionRutas rp 
    INNER JOIN ExpresoBrasilia_Lappiz_PuntosAbordaje pa on pa.Id=rp.PuntodeAbordaje   
    INNER JOIN ExpresoBrasilia_Lappiz_Localidades l on l.id=rp.Localidad
    WHERE pa.CENombre = '${cityBeforeDash}'
  `;
  }

  if (option == 2) {
  // Asegúrate de validar la entrada:
  const safeCity = destCity.replace(/'/g, "''"); // Escapa comillas simples
  const cityBeforeDashdest = safeCity.split('-')[0].trim();

  query = `
    SELECT 
      rp.*,
      l.Id as localidad_id,
      l.CENombre as localidad
    FROM ExpresoBrasilia_Lappiz_GestionRutas rp 
    INNER JOIN ExpresoBrasilia_Lappiz_PuntosAbordaje pa on pa.Id=rp.PuntodeAbordaje   
    INNER JOIN ExpresoBrasilia_Lappiz_Localidades l on l.id=rp.Localidad
    WHERE pa.CENombre = '${cityBeforeDashdest}'
  `;
}



  //const query = "SELECT rf.Id as ruta_id,rf.CERuta as ruta, rf.CiudadOrigenRuta as ciudad_ruta_id,pa.Id as parada_id,pa.CEParada as parada,pa.Valor,pab.CENombre as puntoabordaje FROM ExpresoBrasilia_Lappiz_ParadasRutaFija pa LEFT JOIN ExpresoBrasilia_Lappiz_RutaFija rf ON rf.Id=pa.RutaForenKey INNER JOIN ExpresoBrasilia_Lappiz_PuntosAbordaje pab on pab.Id=rf.Puntoabordaje";

executeQueryLappiz(query)
  .then(res => {
    console.log("✔️ Respuesta original:", res);

    // --- 1️⃣ Parsear si viene como string ---
    if (typeof res === 'string') {
      try { res = JSON.parse(res); }
      catch (e) {
        console.error("❌ Error al parsear respuesta:", e);
        return;
      }
    }

    // --- 2️⃣ Aplanar manualmente sin usar .flat() ---
    const flat = [];
    if (Array.isArray(res)) {
      res.forEach(arr => {
        if (Array.isArray(arr)) arr.forEach(r => flat.push(r));
        else if (arr && typeof arr === 'object') flat.push(arr);
      });
    } else {
      console.warn("⚠️ La respuesta no es un array válido:", res);
      return;
    }

    console.log("📦 Datos aplanados:", flat);

    // --- 3️⃣ Eliminar duplicados por 'Id' ---
    const seen = new Set();
    const unique = flat.filter(r => {
      if (!r || !r.Id) return false;
      if (seen.has(r.Id)) return false;
      seen.add(r.Id);
      return true;
    });

    console.log("🧩 Registros únicos:", unique);

    // --- 4️⃣ Llenar los selects de origen y destino ---
    const $bFrom = $('#barrioFromSelect').empty().append('<option value="">-- Seleccione --</option>');
    const $bTo = $('#barrioToSelect').empty().append('<option value="">-- Seleccione --</option>');

    unique.forEach(item => {
      const label = `Precio: ${item.CEPrecio?.toLocaleString('es-CO') || 0}`;
      // puedes usar otra propiedad si quieres mostrar el nombre del barrio o punto
      $bFrom.append(`<option value="${item.Id}" data-precio="${item.CEPrecio}">${item.localidad}</option>`);
      $bTo.append(`<option value="${item.Id}" data-precio="${item.CEPrecio}">${item.localidad}</option>`);
    });

    // --- 5️⃣ Mostrar confirmación en consola ---
    console.log(`✅ ${unique.length} opciones agregadas en "from" y "to".`);
  })
  .catch(err => console.error("⚠️ Error ejecutando query:", err));



}
function populateVehicles(originCity, destCity) {
  const vehicles = VEHICLES_BY_CITY[originCity] || [];
  const $div = $('#vehicleOptions').empty();
  if(vehicles.length === 0) { $div.text('No hay vehículos configurados en la ciudad.'); return; }
  vehicles.forEach(v => $div.append(`<span class="tag">${v.name} — Capacidad: ${v.capacity}</span>`));
}

/* --------------------------- Filtrar opciones de <select> --------------------------- */
function filterSelectOptions(selector, q) {
  const val = q.toLowerCase();
  $(selector).find('option').each(function(){
    const txt = $(this).text().toLowerCase();
    $(this).toggle(txt.indexOf(val) !== -1);
  });
}

/* --------------------------- POBLAR HORARIOS --------------------------- */
function populateTimesTo(timesArray, ticketDepartureISO, etaMinutes, targetSelector) {
  const ticketDeparture = new Date(ticketDepartureISO);
  $(targetSelector).empty().append('<option value="">-- Seleccione hora --</option>');
  timesArray.forEach(t => {
    const pickDate = toDateOnSameDay(ticketDepartureISO, t);
    const arrivalAtBoarding = new Date(pickDate.getTime() + etaMinutes * 60000);
    const limit = new Date(ticketDeparture.getTime() - 120 * 60000); // 2 horas antes
    if (arrivalAtBoarding <= limit) {
      $(targetSelector).append(
        `<option value="${t}">${t} (llegada ${arrivalAtBoarding.toTimeString().slice(0, 5)})</option>`
      );
    }
  });
  if ($(targetSelector).find('option').length === 1)
    $(targetSelector).append(
      '<option disabled>Sin horarios disponibles que cumplan la condición (llegar 2h antes)</option>'
    );
}

function populateTimesFrom(timesArray, ticketArrivalISO, targetSelector) {
  // Generar intervalos fijos de 1 hora (00:00, 01:00, ..., 23:00)
  const fixedTimes = [];
  for (let h = 0; h < 24; h++) {
    const hour = h.toString().padStart(2, '0');
    fixedTimes.push(`${hour}:00`);
  }

  $(targetSelector).empty().append('<option value="">-- Seleccione hora --</option>');

  // Insertar las horas generadas directamente, sin filtros ni dependencias
  fixedTimes.forEach(t => {
    $(targetSelector).append(`<option value="${t}">${t}</option>`);
  });

  if ($(targetSelector).find('option').length === 1)
    $(targetSelector).append('<option disabled>Sin horarios disponibles</option>');
}

/* --------------------------- Construir formulario de pasajeros --------------------------- */
function buildPassengersForm() {
  const total = parseInt($('#passengerCount').val()) || 1;
  const $c = $('#passengersContainer').empty();
  for(let i=1;i<=total;i++){
    const html =
      `<div class="card mb-2">
        <div class="card-body">
          <h6>Pasajero ${i}</h6>
          <div class="row">
            <div class="col-md-4 mb-2">
              <label class="form-label">Nombres</label>
              <input id="pax_name_${i}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-label">Apellidos</label>
              <input id="pax_last_${i}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-label">Tipo de documento</label>
              <select id="pax_idtype_${i}" class="form-select">${ID_TYPES.map(t=>`<option>${t}</option>`).join('')}</select>
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-label">Número documento</label>
              <input id="pax_idnumber_${i}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-label">Correo electrónico</label>
              <input id="pax_email_${i}" class="form-control" placeholder="ejemplo@dominio.com" required>
            </div>
            <div class="col-md-2 mb-2">
              <label class="form-label">País</label>
              <select id="pax_country_${i}" class="form-select">${COUNTRY_CODES.map(c=>`<option value="${c.code}">${c.code} ${c.name}</option>`).join('')}</select>
            </div>
            <div class="col-md-2 mb-2">
              <label class="form-label">Celular (10 dígitos)</label>
              <input id="pax_phone_${i}" class="form-control" placeholder="3001234567" required>
            </div>
          </div>
        </div>
      </div>`;
    $c.append(html);
  }
}

/* --------------------------- Cálculo de tarifa (igual) --------------------------- */
function calculateAndShowCost() {
  const ticketOpt = $('#ticketSelect').find('option:selected');
  const origin = ticketOpt.data('origin');
  const destination = ticketOpt.data('destination');
  const service = $('#serviceType').val();
  const pax = parseInt($('#passengerCount').val()) || 1;
  let tariff = TARIFFS.find(t => t.service === service && t.origin === origin && t.destination === destination);
  if(!tariff) { tariff = { base: 100000 }; }
  const vehicles = (VEHICLES_BY_CITY[origin] || []);
  const multiplier = (vehicles.length>0) ? vehicles[0].price_multiplier : 1.0;
  const basePerPax = Math.round(tariff.base * multiplier);
  const subtotal = basePerPax * pax;
  const serviceFee = Math.round(subtotal * 0.05);
  const taxes = Math.round(subtotal * 0.12);
  const total = subtotal + serviceFee + taxes;
  $('#costSummary').html(
    `<h6>Resumen de costos</h6>
     <div>Tarifa base por pasajero: <strong>${basePerPax.toLocaleString('es-CO')}</strong></div>
     <div>Subtotal (${pax} pax): <strong>${subtotal.toLocaleString('es-CO')}</strong></div>
     <div>Servicio: <strong>${serviceFee.toLocaleString('es-CO')}</strong></div>
     <div>Impuestos: <strong>${taxes.toLocaleString('es-CO')}</strong></div>
     <hr>
     <div><strong>Total a pagar: ${total.toLocaleString('es-CO')}</strong></div>`
  );
  $('#transferForm').data('cost', { basePerPax, subtotal, serviceFee, taxes, total });
}

/* --------------------------- Validación email --------------------------- */
function validateEmail(email) {
  if(!email) return false;
  if(email.indexOf(' ') !== -1) return false;
  if((email.match(/@/g)||[]).length !== 1) return false;
  const re = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
  return re.test(email);
}

/* --------------------------- Construir payload final --------------------------- */
function buildPayload(passengers) {
  const ticketOpt = $('#ticketSelect').find('option:selected');
  const payload = {
    requester: { idType: 'cc', idNumber: $('#idNumber').val().trim() },
    ticketId: ticketOpt.val(),
    serviceType: $('#serviceType').val(),
    transferOption: $('#transferOption').val(),
    passengerCount: parseInt($('#passengerCount').val()),
    passengers: passengers,
    routeDetail: {},
    cost: $('#transferForm').data('cost')
  };
  if(payload.serviceType === 'fixed') {
    if($('#fixed_to_boarding_check').is(':checked')) {
      payload.routeDetail.toBoarding = { stopId: $('#stopToSelect').val(), pickupTime: $('#timeToSelect').val() };
    }
    if($('#fixed_from_descent_check').is(':checked')) {
      payload.routeDetail.fromDescent = { stopId: $('#stopFromSelect').val(), boardingTime: $('#timeFromSelect').val() };
    }
  } else if(payload.serviceType === 'custom') {
    if($('#custom_to_boarding_check').is(':checked')) {
      payload.routeDetail.toBoarding = { barrio: $('#barrioToSelect').val(), address: $('#addressTo').val(), pickupTime: $('#timeToCustom').val() };
    }
    if($('#custom_from_descent_check').is(':checked')) {
      payload.routeDetail.fromDescent = { barrio: $('#barrioFromSelect').val(), address: $('#addressFrom').val(), boardingTime: $('#timeFromCustom').val() };
    }
  }
  return payload;
}

/* --------------------------- Funciones relacionadas con la sección titular --------------------------- */
function populateInvoicePassengerSelect() {
  const total = parseInt($('#passengerCount').val()) || 1;
  const $sel = $('#invoicePassengerSelect').empty().append('<option value="">-- Seleccione --</option>');
  for(let i=1;i<=total;i++){
    const name = $(`#pax_name_${i}`).val() || `Pasajero ${i}`;
    const last = $(`#pax_last_${i}`).val() || '';
    $sel.append(`<option value="${i}|${name} ${last}">${i} - ${name} ${last}</option>`);
  }
}
function toggleInvoiceSection(isPassenger) {
  if(isPassenger) {
    $('#invoiceSelectPassenger').removeClass('d-none');
    $('#invoiceHolderForm').addClass('d-none');
  } else {
    $('#invoiceSelectPassenger').addClass('d-none');
    $('#invoiceHolderForm').removeClass('d-none');
    // Default: mostrar campos de persona natural a menos que se seleccione NIT
    $('#invoice_id_type').trigger('change');
  }
}
function resetInvoiceSection() {
  // por defecto: sí es pasajero
  $('input[name="invoice_is_passenger"][value="yes"]').prop('checked', true);
  toggleInvoiceSection(true);
  $('#invoicePassengerSelect').val('');
  $('#invoice_id_type').val('');
  $('#invoice_id_number').val('');
  $('#invoice_id_dig').val('');
  $('#invoice_razon_social').val('');
  $('#invoice_email_nit').val('');
  $('#invoice_first_name').val('');
  $('#invoice_second_name').val('');
  $('#invoice_first_lastname').val('');
  $('#invoice_second_lastname').val('');
  $('#invoice_email_natural').val('');
}

function normalizeAmount(amount) {
  let num = parseFloat(amount);

  if (Number.isNaN(num)) {
    throw new Error(`El valor amount no es numérico: ${amount}`);
  }

  // Convertir a string con 2 decimales para analizar
  let [entero, dec] = num.toFixed(2).split(".");
  dec = dec || "00";

  const d1 = parseInt(dec[0]); // primer decimal
  const d2 = parseInt(dec[1]); // segundo decimal

  // Caso especial: exacto .00 → dejar como .0
  if (d1 === 0 && d2 === 0) {
    return `${entero}.0`;
  }

  // Aplicar round half to even
  if (d2 === 5) {
    if (d1 % 2 === 0) {
      // primer decimal par → redondea hacia abajo
      return `${entero}.${d1}`;
    } else {
      // primer decimal impar → redondea hacia arriba
      return `${entero}.${d1 + 1}`;
    }
  } else {
    // Redondeo normal a 1 decimal
    return (Math.round(num * 10) / 10).toFixed(1);
  }
}




(async () => {
    // ---------------------------
    // Config y servicios
    // ---------------------------
    await G_addHeaderPages();
    //G_addIdApp_page();
    
})();

async function G_loadScriptAsync(src, id) {
    return new Promise((resolve, reject) => {
        // Verificar si el script ya existe
        if (document.getElementById(id)) {
            resolve(); // Ya está cargado
            return;
        }
        const script = document.createElement('script');
        script.src = src;
        script.id = id;
        script.onload = () => resolve();
        script.onerror = () => reject(new Error(`Error al cargar el script: ${src}`));
        document.head.appendChild(script);
    });
}

async function G_addHeaderPages() {
    if (document.getElementById('header-expreso-brasilia'))
        return;
    await G_loadScriptAsync("https://cdn.jsdelivr.net/npm/pouchdb@8.0.1/dist/pouchdb.min.js", "cdn_pouch");
    const htmlHeader = `
    <section class="hero" id="header-expreso-brasilia">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-logo">UNITRANSCO</h1>
        </div>
    </section>`;

    document.querySelector('body').insertAdjacentHTML('afterbegin', htmlHeader); 
    G_addStylePages();
    G_addModalResumen();
}



function G_addStylePages() {
    if (document.getElementById('style-expreso-brasilia'))
        return;
    const styleGlobal = `<style id="style-expreso-brasilia">
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f2f2f2;
}

#app-shell {
    min-height: 100vh;
    position: relative;
}

.hero {
    height: 320px;
    background: url('https://static.expresobrasilia.com/wp-content/uploads/2024/06/banner-3-1.jpg') no-repeat center center;
    background-size: cover;
    position: relative;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(10, 32, 60, 0.65);
}

.hero-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-logo {
    color: white;
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: 3px;
}

.main-card {
    position: relative;
    margin-top: -60px;
    background: #ffffff;
    border-top-left-radius: 40px;
    border-top-right-radius: 40px;
    padding: 2rem 1.5rem 6rem 1.5rem;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
    min-height: calc(100vh - 260px);
}

.card-content {
    max-width: 500px;
    margin: 0 auto;
}

.main-card h2 {
    font-size: 1.5rem;
    color: #163b63;
    margin-bottom: 0.5rem;
}

.main-card p {
    color: #666;
    margin-bottom: 1.5rem;
}

.form-control {
    background: #f1f3f6;
    border: none;
    border-radius: 18px;
    padding: 1rem;
    font-size: 1rem;
    margin-bottom: 1.2rem;
}
.k-dropdown-wrap{
border: none !important;
}

.k-combobox:focus{
    background: #f1f3f6;
}
.k-input{
    border-radius: 25px !important; 
    background: #f1f3f6;
}
.k-combobox>.k-state-focused{
    border-radius: 25px !important;
}
.form-control:focus{
    border-radius: 25px !important;
    background: #f1f3f6;
}
    
.k-combobox .k-select{
    background: #f1f3f6;
    border: none;
    border-radius: 0px 25px 25px 0px !important; 
}
.k-input-button.k-button.k-icon-button {
    border: none;
    background: #f1f3f6;
}

label {
    font-weight: 600;
    color: #1c355e;
    margin-bottom: 0.5rem;
    display: block;
}

.btn-primary {
    width: 100% !important;
    border-radius: 40px !important;
    padding: 7px !important;
    font-size: 1.2rem !important;
    font-weight: 600 !important;
    background: #163b63 !important;
    border: none !important;
    color: #fff !important;
    margin-top: 20px !important;
}

.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #f8f8f8;
    display: flex;
    justify-content: space-around;
    padding: 0.8rem 0;
    border-top-left-radius: 25px;
    border-top-right-radius: 25px;
    box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
}

.nav-item {
    background: none;
    border: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #bbb;
    font-size: 0.8rem;
}

.nav-item i {
    font-size: 1.4rem;
    margin-bottom: 5px;
}

.nav-item.active {
    color: #163b63;
    font-weight: 600;
}
button.nav-item.active {
    background-color: #f8f8f8 !important;
}</style>`;
    document.querySelector('head').insertAdjacentHTML('beforeend', styleGlobal);

}
function G_addModalResumen() {
    if (document.getElementById('resumenModal'))
        return;
    const htmlModalResumen = `<div class="modal fade" id="resumenModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content shadow-lg rounded-lg">

            <!-- Header -->
            <div class="modal-header bg-expreso text-white">
                <h5 class="modal-title font-weight-bold">Resumen de tu compra</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body px-3 py-2">
                <div id="resumenLayout" class="d-flex flex-column gap-3">

                    <!-- Pasajeros -->
                    <div id="resumenPasajeros" class="card resumen-card">
                        <div class="card-body p-2">
                            <h6 class="card-title text-primary mb-2">
                                <i class="fas fa-user-friends mr-1"></i> Pasajeros
                            </h6>
                            <ul class="list-group list-group-flush small">
                                <!-- Cantidad y nombres de pasajeros -->
                            </ul>
                        </div>
                    </div>

                    <!-- Ida -->
                    <div id="resumenIda" class="card resumen-card">
                        <div class="card-body p-2">
                            <h6 class="card-title text-success mb-2">
                                <i class="fas fa-arrow-right mr-1"></i> Viaje de ida
                            </h6>
                            <div class="small resumen-detalle">
                                <!-- Costo individual, sub total, descuentos, costos adicionales, total a pagar -->
                            </div>
                        </div>
                    </div>

                    <!-- Regreso -->
                    <div id="resumenRegreso" class="card resumen-card d-none">
                        <div class="card-body p-2">
                            <h6 class="card-title text-info mb-2">
                                <i class="fas fa-arrow-left mr-1"></i> Viaje de regreso
                            </h6>
                            <div class="small resumen-detalle">
                                <!-- Costo individual, sub total, descuentos, costos adicionales, total a pagar -->
                            </div>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div id="resumenTotales" class="card resumen-card border-0 bg-light">
                        <div class="card-body p-3">
                            <h5 class="mb-2 font-weight-bold">Total a pagar</h5>
                            <p class="h4 text-primary font-weight-bold mb-0">
                                $ <span id="totalGeneral">0</span>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>`;
    document.querySelector('body').insertAdjacentHTML('beforeend', htmlModalResumen);
    setTimeout(() => {
        $("#resumenCompra").click(() => {
            $("#resumenModal").modal("show");
        });
    }, 1000);
}



</script>
</body>
</html>

