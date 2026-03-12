<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Programa tu traslado</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .step { display:none; }
    .step.active { display:block; }
    .small-muted { font-size:0.9rem; color:#6c757d; }
    .search-input { margin-bottom:8px; }
    .invalid-feedback { display:block; }
    .cursor-pointer { cursor:pointer; }
    .tag { background:#eef; padding:3px 8px; border-radius:12px; margin-right:6px; }
    .btn-brasilia { background:#0d6efd; color:#fff; border:none; }

    .card-usado {
  border: 2px solid red !important;
  position: relative;
  opacity: 0.85;
}

.card-usado::after {
  content: "USADO";
  position: absolute;
  top: 8px;
  right: 8px;
  background: red;
  color: white;
  padding: 3px 8px;
  font-size: 11px;
  border-radius: 4px;
  font-weight: bold;
}
  </style>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h5>Expreso Brasilia</h5>
    <small>Viajando con tus sueños</small>
    <button class="btn btn-outline-secondary mt-3 d-none" id="resumenCompra" style="position: fixed; top: 0; right: 15px; padding: 0.5rem; z-index: 1;">Ver compra</button>
    <div id="timer" class="d-none" style="font-size:10px;font-weight:bold;top: 50px;right: 25px;position: fixed;color: black;z-index: 1;">Tiempo: <span id="timerValue">10:00</span></div>
</header>
<div class="container my-4" id="mainContent">
  <h2 class="title-ppal">Consulta</h2>
  <div id="alertPlaceholder"></div>

  <div class="card">
    <div class="card-body">
     
        <div class="input-group">
            <input type="text" id="documentoInput" class="form-control" placeholder="Número de documento">
            <button class="btn btn btn-brasilia" id="btnConsultarTraslados">Consultar</button>
        </div>

        <div id="resultadoTraslados" class="mt-3"></div>

    </div>
  </div>
</div>







<!-- jQuery + Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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

function showAlert(msg, type = 'info', reload = false) {
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
    confirmButtonText: 'Aceptar'
  }).then(() => {
    if (reload) {
      location.reload();
    }
  });
}

$(document).on("click", "#btnConsultarTraslados", async function () {
    const documento = $("#documentoInput").val().trim();

    if (documento === "") {
        showAlert("Ingrese un número de documento", "warning");
        return;
    }

    let query = `
        SELECT 
            t.*
        FROM ExpresoBrasilia_Lappiz_Traslados t
        WHERE t.Nmerodocumento = '${documento}'
    `;

    $("#resultadoTraslados").html(`<div class='text-center mt-3'>Consultando...</div>`);

    try {
        const res = await executeQueryLappiz(query);
        let info = normalizeResponse(res);

        if (!info) {
            $("#resultadoTraslados").html("");
            return;
        }

        // Si devuelve 1 objeto, lo convertimos en array para iterarlo
        if (!Array.isArray(info)) info = [info];

        
        pintarTraslados(info);

    } catch (err) {
        console.error("Error consultando:", err);
        showAlert("Error al consultar traslados", "danger");
    }
});

function pintarTraslados(lista) {
    if (!lista || lista.length === 0) {
        $("#resultadoTraslados").html(`<div class="alert alert-warning">No se encontraron traslados</div>`);
        return;
    }

    let html = `
        <h5 class="mt-3">Traslados encontrados (${lista.length})</h5>
        <div class="row g-3 mt-2">
    `;

    lista.forEach(item => {
        let info = {};

        // Parsear el JSON del traslado
        try {
            info = JSON.parse(item.Infotraslado);
        } catch (e) {
            console.error("Error parseando Infotraslado:", e, item.Infotraslado);
            info = {};
        }

        // Si está usado → clase especial
        const usadoClass = item.Usado == 1 ? "card-usado" : "";

        // Construcción del QR
        const qrData = JSON.stringify({
            nombre: item.CENombre,
            apellidos: item.Apellidos,
            doc: item.Nmerodocumento,
            ref: item.Reference
        });

        html += `
            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                <div class="card shadow-sm h-100 ${usadoClass}">
                    <div class="card-body">

                        <h6 class="card-title">
                            <strong>Ticket:</strong> ${info.ticketId ?? 'N/A'}
                        </h6>

                        <p class="mb-1"><strong>Barrio:</strong> ${info.barrio ?? 'N/A'}</p>
                        <p class="mb-1"><strong>Dirección:</strong> ${info.address ?? 'N/A'}</p>
                        <p class="mb-1"><strong>Punto:</strong> ${info.punto ?? 'N/A'}</p>
                        <p class="mb-1"><strong>Hora:</strong> ${info.hora ?? 'N/A'}</p>
                        <p class="mb-1"><strong>Ruta:</strong> ${info.ruta ?? 'N/A'}</p>
                        <p class="mb-1"><strong>PickupTime:</strong> ${info.pickupTime ?? 'N/A'}</p>
                        <p class="mb-1"><strong>Transfer Option:</strong> ${info.transferOption ?? 'N/A'}</p>
                        <p class="mb-1"><strong>ServiceType:</strong> ${info.serviceType ?? 'N/A'}</p>

                        <span class="badge bg-primary mt-2">
                            Referencia: ${item.Reference ?? 'N/A'}
                        </span>

                        <!-- CONTENEDOR DEL QR -->
                        <div id="qr_${item.Id}" class="text-center mt-3"></div>

                    </div>
                </div>
            </div>
        `;
    });

    html += `</div>`;
    $("#resultadoTraslados").html(html);

    /* ---------- GENERAR QR DESPUÉS DE RENDERIZAR ---------- */
    lista.forEach(item => {
        const qrData = JSON.stringify({
            nombre: item.CENombre,
            apellidos: item.Apellidos,
            doc: item.Nmerodocumento,
            ref: item.Reference
        });

        new QRCode(document.getElementById("qr_" + item.Id), {
            text: qrData,            
        });
    });
}








 function normalizeResponse(res) {

    /* ---------- 1) Si viene como string, intentar parsear ---------- */
    if (typeof res === "string") {
        try {
            res = JSON.parse(res);
            console.log("Respuesta parseada desde string:", res);
        } catch (e) {
            console.error("No se pudo parsear res como JSON:", e);
            showAlert("Error al leer la respuesta del servidor", "danger");
            return null;
        }
    }

    /* ---------- 2) Aplanar ---------- */
    let flat;
    try {
        flat = res.flat();
    } catch (e) {
        console.error("No se pudo aplanar la respuesta:", e);
        showAlert("Formato de datos inválido", "danger");
        return null;
    }

    /* ---------- 3) Quitar duplicados por Id ---------- */
    const unique = flat.filter((v, i, arr) =>
        arr.findIndex(x => x.Id === v.Id) === i
    );

    console.log("Resultado sin duplicados:", unique);

    /* ---------- 4) Validar información ---------- */
    if (unique.length === 0) {
        showAlert("No se encontró información", "warning");
        return null;
    }

    /* ---------- 5) Si solo hay uno, retorno el objeto directamente */
    if (unique.length === 1) {
        return unique[0];
    }

    /* ---------- 6) Si hay varios, retorno el array completo ---------- */
    return unique;
}


</script>
</body>
</html>
