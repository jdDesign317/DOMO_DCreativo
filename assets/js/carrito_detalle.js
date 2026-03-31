// Script básico para manejar acciones en la tabla del carrito
document.addEventListener("DOMContentLoaded", () => {
    const tabla = document.getElementById("tablaDetalles");
    const totalGeneralSpan = document.getElementById("totalGeneral");
    const btnAgregarFila = document.getElementById("btnAgregarFila");

    // Función para recalcular subtotales y total general
    function recalcularTotales() {
        let total = 0;
        const filas = tabla.querySelectorAll("tbody tr");

        filas.forEach(fila => {
            const cantidadInput = fila.querySelector(".cant");
            const precioCell = fila.querySelector("td:nth-child(3)");
            const subtotalCell = fila.querySelector(".subtotal");

            const cantidad = parseInt(cantidadInput.value) || 0;
            const precio = parseFloat(precioCell.textContent.replace("$", "")) || 0;
            const subtotal = cantidad * precio;

            subtotalCell.textContent = `$${subtotal.toFixed(2)}`;
            total += subtotal;
        });

        totalGeneralSpan.textContent = total.toFixed(2);
        document.getElementById("totalEstimadoTexto").textContent = `$${total.toFixed(2)}`;
    }

    // Escuchar cambios en cantidad
    tabla.addEventListener("input", (e) => {
        if (e.target.classList.contains("cant")) {
            recalcularTotales();
        }
    });

    // Eliminar fila
    tabla.addEventListener("click", (e) => {
        if (e.target.closest(".btn-eliminar")) {
            const fila = e.target.closest("tr");
            fila.remove();
            recalcularTotales();
        }
    });

    // Agregar fila nueva (opcional)
    if (btnAgregarFila) {
        btnAgregarFila.addEventListener("click", () => {
            const nuevaFila = document.createElement("tr");
            nuevaFila.innerHTML = `
                <td><select class="form-select product-select"><option>Nuevo producto</option></select></td>
                <td><input type="number" class="form-control cant small-input" min="1" value="1"></td>
                <td>$0.00</td>
                <td class="subtotal">$0.00</td>
                <td><button class="btn btn-sm btn-outline-danger btn-eliminar"><i class="bi bi-trash"></i></button></td>
            `;
            tabla.querySelector("tbody").appendChild(nuevaFila);
            recalcularTotales();
        });
    }

    // Inicial recalculo
    recalcularTotales();
});
