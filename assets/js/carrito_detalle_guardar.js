// Guardar cambios del carrito vía AJAX
document.addEventListener("DOMContentLoaded", () => {
    const btnGuardar = document.getElementById("btnGuardar");

    if (btnGuardar) {
        btnGuardar.addEventListener("click", () => {
            const filas = document.querySelectorAll("#tablaDetalles tbody tr");
            const detalles = [];

            filas.forEach(fila => {
                const id_detalle = fila.dataset.id || null;
                const id_producto = fila.querySelector(".product-select")?.value || null;
                const cantidad = fila.querySelector(".cant")?.value || 1;

                detalles.push({
                    id_detalle: id_detalle,
                    id_producto: id_producto,
                    cantidad: cantidad
                });
            });

            fetch("../controladores/CarritoDetalleControlador.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    id_carrito: window.ID_CARRITO,
                    detalles: detalles
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Carrito guardado correctamente ✅");
                    location.reload();
                } else {
                    alert("Error al guardar ❌");
                }
            })
            .catch(err => console.error("Error AJAX:", err));
        });
    }
});
