<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <form id="formMensaje" class="row g-3">
      <div class="col-md-3">
        <label class="form-label fw-semibold">Usuario</label>
        <input type="number" name="id_usuario" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Remitente</label>
        <input type="text" name="remitente" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Mensaje</label>
        <textarea name="mensaje" class="form-control" rows="2" required></textarea>
      </div>
      <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-danger px-4">
          <i class="bi bi-send me-1"></i> Enviar
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Contenedor de mensajes -->
<div id="listaMensajes" class="card shadow-sm">
  <div class="card-body">
    <ul class="list-group" id="mensajesUl"></ul>
  </div>
</div>

<script>
async function cargarMensajes() {
  const res = await fetch("../vistas/mensajes_api.php");
  const data = await res.json();
  const ul = document.getElementById("mensajesUl");
  ul.innerHTML = "";
  data.forEach(m => {
    ul.innerHTML += `<li class="list-group-item">
      <strong>${m.remitente}</strong>: ${m.mensaje}
      <span class="text-muted small float-end">${m.fecha}</span>
    </li>`;
  });
}

// Enviar mensaje asincrónicamente
document.getElementById("formMensaje").addEventListener("submit", async e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch("../vistas/mensajes_api.php", { method: "POST", body: formData });
  const result = await res.json();
  if (result.success) {
    e.target.reset();
    cargarMensajes();
  } else {
    alert("Error al enviar mensaje");
  }
});

// Cargar mensajes cada 5 segundos
setInterval(cargarMensajes, 5000);
cargarMensajes();
</script>
