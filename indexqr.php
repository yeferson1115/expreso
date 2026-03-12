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
  <h2 class="title-ppal">Validar traslado</h2>
  <div id="alertPlaceholder"></div>

  <div class="card">
    <div class="card-body">
      <div id="qr-reader" style="width: 100%; max-width: 400px; margin-bottom: 1rem;"></div>
      <div id="qr-result" class="alert alert-info d-none"></div>
    </div>
  </div>
</div>







<!-- jQuery + Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
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



$.getScript("https://unpkg.com/html5-qrcode@2.3.7/html5-qrcode.min.js")
  .done(function () {
      console.log("Librería cargada correctamente");

      const html5QrcodeScanner = new Html5Qrcode("qr-reader");

      Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
          const cameraId = cameras[0].id;
          html5QrcodeScanner.start(
            cameraId,
            {
              fps: 10,
              qrbox: 250
            },
            qrCodeMessage => {
              $('#qr-result').removeClass('d-none').text('Datos QR: ' + qrCodeMessage);

              try {
                const data = JSON.parse(qrCodeMessage);
                const documento = data.doc || "";
                const referencia = data.ref || "";
                console.log("Documento:", documento);
                console.log("Referencia:", referencia);
                console.log('Datos del QR:', data);
                
                let query = `
                  SELECT 
                  t.*
                  FROM ExpresoBrasilia_Lappiz_Traslados t
                  WHERE t.Nmerodocumento = '${documento}' and t.Reference = '${referencia}'
                `;

                executeQueryLappiz(query)
                .then(res => {
                  
                  // 👇 Verificamos en consola
                  console.log("informacion:", res);

                   if (typeof res === "string") {
                      try {
                        res = JSON.parse(res);
                        console.log("Respuesta parseada desde string:", res);
                      } catch (e) {
                        console.error("No se pudo parsear res como JSON:", e);
                        showAlert("Error al leer la respuesta del servidor", "danger");
                        return;
                      }
                    }

                                  // 1️⃣ aplanar
                      let flat = res.flat();

                      // 2️⃣ quitar duplicados
                      let unique = flat.filter((v, i, arr) =>
                          arr.findIndex(x => x.Id === v.Id) === i
                      );

                      console.log("Resultado sin duplicados:", unique);

                      // 3️⃣ validar que exista info
                      if (unique.length === 0) {
                          showAlert("No se encontró información para este QR", "warning");
                          return;
                      }

                      let registro = unique[0];

                      // 4️⃣ parsear Infotraslado
                      let info = {};
                      try {
                          info = JSON.parse(registro.Infotraslado);
                      } catch (e) {
                          console.error("Error parseando Infotraslado:", e);
                      }

                      if(registro.Usado==null){

                        let queryUpdate = `
                        UPDATE ExpresoBrasilia_Lappiz_Traslados  SET Usado=1
                        WHERE id = '${registro.Id}'
                      `;
                       executeQueryLappiz(queryUpdate)
                          .then(res => {
                              console.log('actualizar traslado');
                              console.log(res);
                              
                              
                          });

                        console.log("Infotraslado parseado:", info);

                        // 5️⃣ Puedes acceder así:
                        console.log("Barrio:", info.barrio);
                        console.log("Dirección:", info.address);
                        console.log("Punto:", info.punto);
                        console.log("Hora:", info.hora);
                        console.log("Ruta:", info.ruta);
                        console.log("PickupTime:", info.pickupTime);
                        console.log("transferOption:", info.transferOption);
                        console.log("serviceType:", info.serviceType);
                        console.log("ticketId:", info.ticketId);
                        if(info.serviceType=='fixed'){
                          let queryruta = `
                            SELECT 
                            r.*
                            FROM ExpresoBrasilia_Lappiz_RutaFija r
                            WHERE r.Id = '${info.ruta}'
                          `;
                          executeQueryLappiz(queryruta)
                          .then(res => {
                              console.log('info ruta');
                              console.log(res);
                              const resultado = normalizeResponse(res);
                              console.log('parceado');
                              console.log(resultado);
                              showAlert('Pasajero:' + data.nombre+' '+ data.apellidos+'<br>'+'Ruta:'+resultado.CERuta+'<br>Hora:'+info.hora, 'success',true);
                          });
                        }
                        
                       

                      }else{
                        showAlert('El Traslado ya fue utilizado', 'info',true);
                      }

                      
           
                })
                .catch(err => console.error("⚠️ Error ejecutando query:", err));

                
              } catch {
                showAlert('QR leído: ' + qrCodeMessage, 'info');
              }

              // Opcional: detener escaneo después de leer un QR válido
              html5QrcodeScanner.stop().catch(e => console.error(e));
            },
            errorMessage => {
              // Puedes mostrar errores de lectura aquí si quieres
              // console.log('Error lectura QR:', errorMessage);
            }
          ).catch(err => {
            showAlert('Error iniciando escáner: ' + err, 'danger');
          });
        } else {
          showAlert('No se encontraron cámaras disponibles.', 'warning');
        }
      }).catch(err => {
        showAlert('Error obteniendo cámaras: ' + err, 'danger');
      });
  })
  .fail(function () {
      console.error("Error al cargar html5-qrcode");
  });



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
