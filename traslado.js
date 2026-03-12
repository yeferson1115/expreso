// Datos parametrizados simulados (reemplazar por llamadas AJAX)
const tiposIdentificacion = [
  "Cédula de ciudadanía",
  "Tarjeta de identidad",
  "Registro civil",
  "Pasaporte",
  "Cédula de extranjería",
  "Carnet diplomático",
  "Acta de nacimiento",
  "Cédula de identidad",
  "Permiso especial de permanencia (PEP)",
  "Permiso por protección temporal (PPT)",
  "Salvoconducto",
  "Tarjeta Andina",
  "Documento extranjero"
];

const ciudadesHabilitadas = [
  { id: 1, nombre: "Ciudad A", tieneTraslado: true, barrios: ["Barrio 1", "Barrio 2", "Barrio 3"], paradas: ["Parada 1", "Parada 2", "Parada 3"] },
  { id: 2, nombre: "Ciudad B", tieneTraslado: false, barrios: ["Barrio 4", "Barrio 5"], paradas: ["Parada 4", "Parada 5"] }
];

const horariosSalidaTiquete = ["08:00", "12:00", "16:00", "20:00"]; // Horas de salida de tiquetes (ejemplo)
const horariosRutaFija = ["05:00", "06:00", "07:00", "08:00", "09:00", "10:00"]; // Horarios recogida ruta fija
const horariosRutaPersonalizada = ["05:00", "06:00", "07:00", "08:00", "09:00", "10:00"]; // Horarios recogida ruta personalizada

// Simulación de tiquetes activos para un cliente
const tiquetesActivos = [
  { id: 101, origen: "Ciudad A", destino: "Ciudad B", horaSalida: "12:00", horaLlegada: "15:00" },
  { id: 102, origen: "Ciudad B", destino: "Ciudad A", horaSalida: "16:00", horaLlegada: "19:00" }
];


$(function () {
  const $app = $("#app");

  // Paso 2.1.3 - Ingresar info pasajero
  function renderIngresoInfoPasajero() {
    let html = `
      <h4>Ingrese información del pasajero</h4>
      <form id="form-identificacion" class="mb-3">
        <div class="mb-3">
          <label for="tipoId" class="form-label">Tipo de identificación</label>
          <select id="tipoId" class="form-select" required>
            <option value="">Seleccione...</option>
            ${tiposIdentificacion.map(t => `<option value="${t}">${t}</option>`).join('')}
          </select>
        </div>
        <div class="mb-3">
          <label for="numeroId" class="form-label">Número de identificación</label>
          <input type="text" id="numeroId" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Verificar tiquetes vigentes</button>
      </form>
      <div id="mensaje-verificacion"></div>
    `;
    $app.html(html);

    $("#form-identificacion").on("submit", function (e) {
      e.preventDefault();
      const tipoId = $("#tipoId").val();
      const numeroId = $("#numeroId").val().trim();

      if (!tipoId || !numeroId) {
        alert("Debe ingresar tipo y número de identificación");
        return;
      }

      // Aquí se simula verificación de tiquete vigente
      // En producción, llamar servicio que valide tipoId y numeroId
      // Simulamos que si numeroId termina en 1 tiene tiquete vigente
      const tieneTiquete = numeroId.endsWith("1035424873");

      if (tieneTiquete) {
        renderSeleccionTiquete();
      } else {
        $("#mensaje-verificacion").html(`<div class="alert alert-danger">No se encontraron tiquetes vigentes para esta identificación.</div>`);
      }
    });
  }

  // Paso 2.1.6 - Seleccionar tiquete, ruta, hora, etc.
  function renderSeleccionTiquete() {
    // Para simplicidad mostramos todos los tiquetes activos (en producción filtrar por usuario)
    let html = `
      <h4>Seleccione tiquete y características del traslado</h4>
      <form id="form-trayecto" class="mb-3">
        <div class="mb-3">
          <label for="tiqueteSelect" class="form-label">Tiquete</label>
          <select id="tiqueteSelect" class="form-select" required>
            <option value="">Seleccione tiquete</option>
            ${tiquetesActivos.map(t => `<option value="${t.id}" data-origen="${t.origen}" data-destino="${t.destino}" data-horasalida="${t.horaSalida}" data-horallegada="${t.horaLlegada}">Origen: ${t.origen} - Destino: ${t.destino} - Salida: ${t.horaSalida}</option>`).join('')}
          </select>
        </div>

        <div class="mb-3">
          <label for="tipoServicio" class="form-label">Tipo de servicio</label>
          <select id="tipoServicio" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="ruta_fija">Ruta fija</option>
            <option value="ruta_personalizada">Ruta personalizada</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Opción de traslado</label>
          <div>
            <div class="form-check form-check-inline">
              <input class="form-check-input opcion-traslado" type="checkbox" id="trasladoHacia" value="hacia" />
              <label class="form-check-label" for="trasladoHacia">Hacia punto de abordaje</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input opcion-traslado" type="checkbox" id="trasladoDesde" value="desde" />
              <label class="form-check-label" for="trasladoDesde">Desde punto de descenso</label>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="cantidadPasajeros" class="form-label">Cantidad de pasajeros</label>
          <input type="number" id="cantidadPasajeros" class="form-control" min="1" max="10" value="1" required />
        </div>

        <div id="camposAdicionales"></div>

        <button type="submit" class="btn btn-success">Continuar</button>
      </form>
    `;

    $app.html(html);

    // Al cambiar tipo de servicio o traslado actualizar campos adicionales
    $("#tipoServicio, .opcion-traslado, #tiqueteSelect").on("change", renderCamposAdicionales);

    $("#form-trayecto").on("submit", function (e) {
      e.preventDefault();
      // Validar y pasar al siguiente paso
      // Por simplicidad solo mostramos mensaje
      alert("Datos de traslado registrados. Aquí se continuaría con registro de pasajeros.");
      // Aquí llamarías renderRegistroPasajeros()
    });
  }

  // Función para mostrar campos adicionales según selección
  function renderCamposAdicionales() {
    const tipoServicio = $("#tipoServicio").val();
    const trasladoHacia = $("#trasladoHacia").is(":checked");
    const trasladoDesde = $("#trasladoDesde").is(":checked");
    const tiqueteSeleccionado = $("#tiqueteSelect option:selected");
    const horaSalida = tiqueteSeleccionado.data("horasalida");
    const horaLlegada = tiqueteSeleccionado.data("horallegada");
    const origen = tiqueteSeleccionado.data("origen");
    const destino = tiqueteSeleccionado.data("destino");

    let html = "";

    // Función para filtrar horarios que permitan llegar 2 horas antes de la salida
    function filtrarHorariosAntes(horarios, horaLimite) {
      // Simple comparación string HH:mm
      return horarios.filter(h => h <= horaLimite);
    }

    // Función para filtrar horarios que sean al menos 1 hora después de llegada
    function filtrarHorariosDespues(horarios, horaLimite) {
      return horarios.filter(h => h >= horaLimite);
    }

    if (!tipoServicio) {
      $("#camposAdicionales").html("");
      return;
    }

    // Campos para traslado hacia punto de abordaje
    if (trasladoHacia) {
      if (tipoServicio === "ruta_fija") {
        // Lugar de recogida: listado paradas origen
        // Hora de recogida: horarios que permitan llegar 2 horas antes de la horaSalida
        const horaLimite = restarHoras(horaSalida, 2);
        const horariosDisponibles = filtrarHorariosAntes(horariosRutaFija, horaLimite);

        html += `<h5>Traslado hacia punto de abordaje (Ruta fija)</h5>`;
        html += `
          <div class="mb-3">
            <label for="lugarRecogida" class="form-label">Lugar de recogida</label>
            <select id="lugarRecogida" class="form-select" required>
              <option value="">Seleccione parada</option>
              ${getParadasPorCiudad(origen).map(p => `<option value="${p}">${p}</option>`).join('')}
            </select>
          </div>
          <div class="mb-3">
            <label for="horaRecogida" class="form-label">Hora de recogida</label>
            <select id="horaRecogida" class="form-select" required>
              <option value="">Seleccione hora</option>
              ${horariosDisponibles.map(h => `<option value="${h}">${h}</option>`).join('')}
            </select>
          </div>
        `;
      } else if (tipoServicio === "ruta_personalizada") {
        // Barrio y dirección, hora recogida validada
        const ciudad = getCiudadPorNombre(origen);
        const horaLimite = restarHoras(horaSalida, 2);
        const horariosDisponibles = filtrarHorariosAntes(horariosRutaPersonalizada, horaLimite);

        html += `<h5>Traslado hacia punto de abordaje (Ruta personalizada)</h5>`;
        html += `
          <div class="mb-3">
            <label for="barrioRecogida" class="form-label">Barrio de recogida</label>
            <select id="barrioRecogida" class="form-select" required>
              <option value="">Seleccione barrio</option>
              ${ciudad.barrios.map(b => `<option value="${b}">${b}</option>`).join('')}
            </select>
          </div>
          <div class="mb-3">
            <label for="direccionRecogida" class="form-label">Dirección de recogida</label>
            <input type="text" id="direccionRecogida" class="form-control" required />
          </div>
          <div class="mb-3">
            <label for="horaRecogida" class="form-label">Hora de recogida</label>
            <select id="horaRecogida" class="form-select" required>
              <option value="">Seleccione hora</option>
              ${horariosDisponibles.map(h => `<option value="${h}">${h}</option>`).join('')}
            </select>
          </div>
        `;
      }
    }

    // Campos para traslado desde punto de descenso
    if (trasladoDesde) {
      if (tipoServicio === "ruta_fija") {
        // Destino final: paradas ciudad destino
        // Hora abordaje: horarios al menos 1 hora después de llegada
        const horaLimite = sumarHoras(horaLlegada, 1);
        const horariosDisponibles = filtrarHorariosDespues(horariosRutaFija, horaLimite);

        html += `<h5>Traslado desde punto de descenso (Ruta fija)</h5>`;
        html += `
          <div class="mb-3">
            <label for="destinoFinal" class="form-label">Destino final</label>
            <select id="destinoFinal" class="form-select" required>
              <option value="">Seleccione parada</option>
              ${getParadasPorCiudad(destino).map(p => `<option value="${p}">${p}</option>`).join('')}
            </select>
          </div>
          <div class="mb-3">
            <label for="horaAbordaje" class="form-label">Hora de abordaje</label>
            <select id="horaAbordaje" class="form-select" required>
              <option value="">Seleccione hora</option>
              ${horariosDisponibles.map(h => `<option value="${h}">${h}</option>`).join('')}
            </select>
          </div>
        `;
      } else if (tipoServicio === "ruta_personalizada") {
        // Barrio y dirección destino, hora abordaje validada
        const ciudad = getCiudadPorNombre(destino);
        const horaLimite = sumarHoras(horaLlegada, 1);
        const horariosDisponibles = filtrarHorariosDespues(horariosRutaPersonalizada, horaLimite);

        html += `<h5>Traslado desde punto de descenso (Ruta personalizada)</h5>`;
        html += `
          <div class="mb-3">
            <label for="barrioDestino" class="form-label">Barrio destino</label>
            <select id="barrioDestino" class="form-select" required>
              <option value="">Seleccione barrio</option>
              ${ciudad.barrios.map(b => `<option value="${b}">${b}</option>`).join('')}
            </select>
          </div>
          <div class="mb-3">
            <label for="direccionDestino" class="form-label">Dirección destino</label>
            <input type="text" id="direccionDestino" class="form-control" required />
          </div>
          <div class="mb-3">
            <label for="horaAbordaje" class="form-label">Hora de abordaje</label>
            <select id="horaAbordaje" class="form-select" required>
              <option value="">Seleccione hora</option>
              ${horariosDisponibles.map(h => `<option value="${h}">${h}</option>`).join('')}
            </select>
          </div>
        `;
      }
    }

    $("#camposAdicionales").html(html);
  }

  // Funciones auxiliares para obtener datos
  function getParadasPorCiudad(nombreCiudad) {
    const ciudad = ciudadesHabilitadas.find(c => c.nombre === nombreCiudad);
    return ciudad ? ciudad.paradas : [];
  }

  function getCiudadPorNombre(nombreCiudad) {
    return ciudadesHabilitadas.find(c => c.nombre === nombreCiudad) || { barrios: [] };
  }

  // Funciones para sumar/restar horas en formato HH:mm
  function restarHoras(hora, cantidad) {
    const [h, m] = hora.split(":").map(Number);
    let fecha = new Date(2000, 0, 1, h, m);
    fecha.setHours(fecha.getHours() - cantidad);
    return fecha.toTimeString().slice(0, 5);
  }

  function sumarHoras(hora, cantidad) {
    const [h, m] = hora.split(":").map(Number);
    let fecha = new Date(2000, 0, 1, h, m);
    fecha.setHours(fecha.getHours() + cantidad);
    return fecha.toTimeString().slice(0, 5);
  }

  // Inicializamos con ingreso de info pasajero
  renderIngresoInfoPasajero();
});


// ... (código anterior, incluyendo variables JSON simuladas) ...

// Objeto para almacenar los datos del traslado a medida que se seleccionan
let trasladoData = {};

$(function () {
  const $app = $("#app");

  // Paso 2.1.3 - Ingresar info pasajero
  function renderIngresoInfoPasajero() {
    // ... (código existente de renderIngresoInfoPasajero) ...

    $("#form-identificacion").on("submit", function (e) {
      e.preventDefault();
      const tipoId = $("#tipoId").val();
      const numeroId = $("#numeroId").val().trim();

      if (!tipoId || !numeroId) {
        alert("Debe ingresar tipo y número de identificación");
        return;
      }

      // Almacenar identificación del pasajero principal
      trasladoData.pasajeroPrincipal = {
        tipoId: tipoId,
        numeroId: numeroId
      };

      // Aquí se simula verificación de tiquete vigente
      const tieneTiquete = numeroId.endsWith("1");

      if (tieneTiquete) {
        renderSeleccionTiquete();
      } else {
        $("#mensaje-verificacion").html(`<div class="alert alert-danger">No se encontraron tiquetes vigentes para esta identificación.</div>`);
      }
    });
  }

  // Paso 2.1.6 - Seleccionar tiquete, ruta, hora, etc.
  function renderSeleccionTiquete() {
    // ... (código existente de renderSeleccionTiquete) ...

    $("#form-trayecto").on("submit", function (e) {
      e.preventDefault();

      // Recopilar todos los datos del formulario de trayecto
      const tiqueteSeleccionado = $("#tiqueteSelect option:selected");
      trasladoData.tiquete = {
        id: tiqueteSeleccionado.val(),
        origen: tiqueteSeleccionado.data("origen"),
        destino: tiqueteSeleccionado.data("destino"),
        horaSalida: tiqueteSeleccionado.data("horasalida"),
        horaLlegada: tiqueteSeleccionado.data("horallegada")
      };
      trasladoData.tipoServicio = $("#tipoServicio").val();
      trasladoData.trasladoHacia = $("#trasladoHacia").is(":checked");
      trasladoData.trasladoDesde = $("#trasladoDesde").is(":checked");
      trasladoData.cantidadPasajeros = parseInt($("#cantidadPasajeros").val(), 10);

      // Recopilar datos específicos de campos adicionales
      if (trasladoData.trasladoHacia) {
        if (trasladoData.tipoServicio === "ruta_fija") {
          trasladoData.recogida = {
            tipo: "ruta_fija",
            lugar: $("#lugarRecogida").val(),
            hora: $("#horaRecogida").val()
          };
        } else if (trasladoData.tipoServicio === "ruta_personalizada") {
          trasladoData.recogida = {
            tipo: "ruta_personalizada",
            barrio: $("#barrioRecogida").val(),
            direccion: $("#direccionRecogida").val(),
            hora: $("#horaRecogida").val()
          };
        }
      }

      if (trasladoData.trasladoDesde) {
        if (trasladoData.tipoServicio === "ruta_fija") {
          trasladoData.destino = {
            tipo: "ruta_fija",
            lugar: $("#destinoFinal").val(),
            hora: $("#horaAbordaje").val()
          };
        } else if (trasladoData.tipoServicio === "ruta_personalizada") {
          trasladoData.destino = {
            tipo: "ruta_personalizada",
            barrio: $("#barrioDestino").val(),
            direccion: $("#direccionDestino").val(),
            hora: $("#horaAbordaje").val()
          };
        }
      }

      // Validar que al menos una opción de traslado esté seleccionada
      if (!trasladoData.trasladoHacia && !trasladoData.trasladoDesde) {
        alert("Debe seleccionar al menos una opción de traslado (Hacia punto de abordaje o Desde punto de descenso).");
        return;
      }

      // Continuar con el registro de pasajeros
      renderRegistroPasajeros();
    });
  }

  // ... (código existente de renderCamposAdicionales y funciones auxiliares) ...


  // *** NUEVA FUNCIÓN: Paso 2.1.7 - Registrar pasajeros ***
  function renderRegistroPasajeros() {
    let html = `
      <h4>Registro de pasajeros</h4>
      <p>Por favor, ingrese los datos de los ${trasladoData.cantidadPasajeros} pasajeros.</p>
      <form id="form-pasajeros" class="mb-4">
    `;

    for (let i = 0; i < trasladoData.cantidadPasajeros; i++) {
      html += `
        <div class="card mb-3">
          <div class="card-header">Pasajero ${i + 1}</div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="tipoIdPasajero${i}" class="form-label">Tipo de identificación</label>
                <select id="tipoIdPasajero${i}" name="tipoId" class="form-select" required>
                  <option value="">Seleccione...</option>
                  ${tiposIdentificacion.map(t => `<option value="${t}">${t}</option>`).join('')}
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="numeroIdPasajero${i}" class="form-label">Número de identificación</label>
                <input type="text" id="numeroIdPasajero${i}" name="numeroId" class="form-control" required />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nombresPasajero${i}" class="form-label">Nombres</label>
                <input type="text" id="nombresPasajero${i}" name="nombres" class="form-control" required />
              </div>
              <div class="col-md-6 mb-3">
                <label for="apellidosPasajero${i}" class="form-label">Apellidos</label>
                <input type="text" id="apellidosPasajero${i}" name="apellidos" class="form-control" required />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="telefonoPasajero${i}" class="form-label">Teléfono</label>
                <input type="tel" id="telefonoPasajero${i}" name="telefono" class="form-control" required />
              </div>
              <div class="col-md-6 mb-3">
                <label for="emailPasajero${i}" class="form-label">Correo electrónico</label>
                <input type="email" id="emailPasajero${i}" name="email" class="form-control" required />
              </div>
            </div>
          </div>
        </div>
      `;
    }

    html += `
        <button type="submit" class="btn btn-primary">Guardar datos de pasajeros</button>
      </form>
      <div id="mensaje-final"></div>
    `;
    $app.html(html);

    // Si el pasajero principal es uno de los que se va a registrar,
    // podemos pre-llenar sus datos si los tuviéramos (ej. nombres, apellidos)
    // Por ahora, solo pre-llenamos el tipo y número de identificación del primer pasajero
    // si la cantidad es 1 y es el pasajero principal.
    if (trasladoData.cantidadPasajeros === 1) {
        $("#tipoIdPasajero0").val(trasladoData.pasajeroPrincipal.tipoId);
        $("#numeroIdPasajero0").val(trasladoData.pasajeroPrincipal.numeroId);
    }


    $("#form-pasajeros").on("submit", function (e) {
      e.preventDefault();

      const pasajeros = [];
      for (let i = 0; i < trasladoData.cantidadPasajeros; i++) {
        const pasajero = {
          tipoId: $(`#tipoIdPasajero${i}`).val(),
          numeroId: $(`#numeroIdPasajero${i}`).val(),
          nombres: $(`#nombresPasajero${i}`).val(),
          apellidos: $(`#apellidosPasajero${i}`).val(),
          telefono: $(`#telefonoPasajero${i}`).val(),
          email: $(`#emailPasajero${i}`).val()
        };
        pasajeros.push(pasajero);
      }
      trasladoData.pasajeros = pasajeros;

      // Aquí se enviaría toda la información a un servicio backend
      console.log("Datos completos del traslado a enviar:", trasladoData);

      // Simulación de envío exitoso
      $("#mensaje-final").html(`
        <div class="alert alert-success">
          ¡Traslado programado con éxito!
          <p>Revisa la consola para ver los datos que se enviarían.</p>
          <p><strong>Resumen:</strong></p>
          <ul>
            <li>Tiquete: ${trasladoData.tiquete.origen} a ${trasladoData.tiquete.destino} (${trasladoData.tiquete.horaSalida})</li>
            <li>Tipo de servicio: ${trasladoData.tipoServicio}</li>
            <li>Pasajeros registrados: ${trasladoData.cantidadPasajeros}</li>
          </ul>
        </div>
        <button class="btn btn-secondary" onclick="location.reload()">Programar otro traslado</button>
      `);
      // Deshabilitar el formulario para evitar reenvíos
      $("#form-pasajeros button[type='submit']").prop('disabled', true);
    });
  }

  // Inicializamos con ingreso de info pasajero
  renderIngresoInfoPasajero();
});

