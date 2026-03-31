const btnGuardar = document.getElementById('btnGuardar');
if (btnGuardar) {
  btnGuardar.addEventListener('click', () => {
    const filas = document.querySelectorAll('#tablaDetalles tbody tr');
    const items = [];
    filas.forEach(tr => {
      const id_detalle = tr.dataset.id || null;
      const sel = tr.querySelector('.product-select');
      const id_producto = sel ? sel.value : null;
      const cantidad = parseInt(tr.querySelector('.cant').value) || 0;
      const precio_unitario = parseFloat(tr.querySelector('.precio').value) || 0;
      if (!id_producto || id_producto === "") return;
      items.push({ id_detalle, id_producto, cantidad, precio_unitario });
    });

    fetch('../ajax/guardar_detalle_presupuesto.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id_presupuesto: window.ID_PRESUPUESTO,
        items: items
      })
    })
    .then(r => r.json())
    .then(resp => {
      if (resp.ok) {
        alert('Guardado correctamente.');
        location.reload();
      } else {
        alert('Error guardando: ' + (resp.error || 'revisá la consola'));
      }
    })
    .catch(err => {
      console.error(err);
      alert('Error de red al guardar.');
    });
  });
}
