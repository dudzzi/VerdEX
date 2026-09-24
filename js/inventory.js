let inventory = [];

let catalog = {
    plant: [],
    fertilizer: [],
    tool: []
};

let currentCategory = "plant";
let currentNoteId = null;

const API_URL =
    window.location.origin + "/api/inventory.php";

const pendingStockUpdates = new Set();

document.addEventListener("DOMContentLoaded", async () => {
    await loadCatalog();
    await loadInventory();
    loadCatalogOptions();
});

async function requestJSON(url, options = {}) {

    const controller = new AbortController();

    const timeout = setTimeout(() => {
        controller.abort();
    }, 20000);

    try {

        const response = await fetch(url, {
            ...options,
            signal: controller.signal,
            cache: "no-store"
        });

        const text = await response.text();

        let data;

        try {
            data = JSON.parse(text);
        } catch (error) {
            console.error("Invalid API response:", text);

            throw new Error(
                "The server returned an invalid response."
            );
        }

        if (!response.ok) {
            throw new Error(
                data.message || `Server error: ${response.status}`
            );
        }

        if (!data.success) {
            throw new Error(
                data.message || "Request failed."
            );
        }

        return data;

    } catch (error) {

        if (error.name === "AbortError") {
            throw new Error(
                "The server took too long to respond."
            );
        }

        throw error;

    } finally {

        clearTimeout(timeout);

    }
}

async function loadCatalog() {

    try {

        const data = await requestJSON(
            API_URL + "?action=catalog"
        );

        catalog = data.catalog || {
            plant: [],
            fertilizer: [],
            tool: []
        };

        return true;

    } catch (error) {

        console.error(error);

        alert(
            "Could not load the catalog.\n\n" +
            error.message
        );

        return false;
    }
}

async function loadInventory() {

    try {

        const data = await requestJSON(
            API_URL + "?action=get"
        );

        inventory = Array.isArray(data.items)
            ? data.items
            : [];

        renderInventory();

        return true;

    } catch (error) {

        console.error(error);

        alert(
            "Could not load inventory.\n\n" +
            error.message
        );

        return false;
    }
}

function renderInventory() {

    const container =
        document.getElementById("inventoryTableBody");

    const empty =
        document.getElementById("emptyMessage");

    const items = inventory.filter(
        item => item.item_type === currentCategory
    );

    if (items.length === 0) {

        container.innerHTML = "";

        empty.style.display = "block";

        return;
    }

    empty.style.display = "none";

    container.innerHTML = items.map(item => {

        const image =
            getInventoryImage(item.image_path);

        const stock =
            Number(item.stock);

        const isLow =
            stock <= 5;

        const statusClass =
            isLow
                ? "low"
                : "good";

        const statusText =
            isLow
                ? "Low Stock"
                : "In Stock";

        const typeText =
            item.item_type === "plant"
                ? "Plant"
                : item.item_type === "fertilizer"
                    ? "Fertilizer"
                    : "Tool";

        return `

            <tr>

                <td>

                    <div class="inventory-item">

                        <img
                            src="${escapeHTML(image)}"
                            class="inventory-item-image"
                            alt="${escapeHTML(item.name)}"
                            onerror="this.src='../images/verdexlogo.png'"
                        >

                        <div>

                            <div class="inventory-item-name">
                                ${escapeHTML(item.name)}
                            </div>

                            <div class="inventory-item-subtitle">
                                Hydroponic inventory
                            </div>

                        </div>

                    </div>

                </td>

                <td>

                    <span class="type-badge">
                        ${typeText}
                    </span>

                </td>

                <td>

                    <div class="stock-value">

                        ${stock}
                        ${escapeHTML(item.unit)}

                    </div>

                    <div class="stock-controls">

                        <button
                            class="stock-control"
                            onclick="changeStock(${item.id}, -1)"
                            ${pendingStockUpdates.has(Number(item.id)) ? "disabled" : ""}>

                            −

                        </button>

                        <button
                            class="stock-control"
                            onclick="changeStock(${item.id}, 1)"
                            ${pendingStockUpdates.has(Number(item.id)) ? "disabled" : ""}>

                            +

                        </button>

                    </div>

                </td>

                <td>

                    <span class="status-badge ${statusClass}">

                        <span class="status-dot"></span>

                        ${statusText}

                    </span>

                </td>

                <td>

                    ${formatDate(item.date_added)}

                </td>

                <td>

                    <button
                        class="view-details-button"
                        onclick="showInfo(${item.id})">

                        View Details

                    </button>

                </td>

            </tr>

        `;

    }).join("");
}

function getInventoryImage(imagePath) {

    if (!imagePath) {
        return "../images/verdexlogo.png";
    }

    if (
        imagePath.startsWith("http://") ||
        imagePath.startsWith("https://") ||
        imagePath.startsWith("../")
    ) {
        return imagePath;
    }

    return "../" + imagePath;
}

function showCategory(category, button) {

    currentCategory = category;

    document
        .querySelectorAll(".category-button")
        .forEach(item => {
            item.classList.remove("active");
        });

    if (button) {
        button.classList.add("active");
    }

    renderInventory();
}

function openAddModal() {

    const type =
        document.getElementById("itemType");

    type.value = currentCategory;

    document
        .getElementById("addModal")
        .classList.add("show");

    loadCatalogOptions();
}

function closeAddModal() {

    document
        .getElementById("addModal")
        .classList.remove("show");
}

function loadCatalogOptions() {

    const type =
        document.getElementById("itemType").value;

    const select =
        document.getElementById("catalogId");

    const label =
        document.getElementById("itemLabel");

    const labels = {
        plant: "Plant",
        fertilizer: "Fertilizer",
        tool: "Tool"
    };

    label.textContent =
        labels[type] || "Item";

    select.innerHTML =
        '<option value="">Select an item</option>';

    const items =
        Array.isArray(catalog[type])
            ? catalog[type]
            : [];

    items.forEach(item => {

        const option =
            document.createElement("option");

        option.value = item.id;
        option.textContent = item.name;

        select.appendChild(option);

    });

    document.getElementById("autoInfoContent").innerHTML =
        "Select an item to view its recommendation.";
}

function showSelectedInformation() {

    const type =
        document.getElementById("itemType").value;

    const id =
        Number(
            document.getElementById("catalogId").value
        );

    const items =
        Array.isArray(catalog[type])
            ? catalog[type]
            : [];

    const item =
        items.find(
            entry => Number(entry.id) === id
        );

    const content =
        document.getElementById("autoInfoContent");

    if (!item) {

        content.innerHTML =
            "Select an item to view its recommendation.";

        return;
    }

    if (type === "plant") {

        content.innerHTML = `
            ${infoField(
                "Recommended Growing Method",
                item.growing_method
            )}

            ${infoField(
                "Recommended Growing Media",
                item.growing_media
            )}

            ${infoField(
                "Recommended Fertilizer",
                item.fertilizer_info
            )}

            ${infoField(
                "Harvest Time",
                item.harvest_time
            )}

            ${infoField(
                "Temperature",
                item.temperature_range
            )}

            ${infoField(
                "Humidity",
                item.humidity_range
            )}

            ${infoField(
                "Care Recommendation",
                item.care
            )}
        `;
    }

    if (type === "fertilizer") {

        content.innerHTML = `
            ${infoField(
                "Recommendation",
                item.description
            )}

            ${infoField(
                "Nutrients",
                item.nutrient_info
            )}

            ${infoField(
                "Application",
                item.application_info
            )}

            ${infoField(
                "Suitable Plants",
                item.suitable_plants
            )}

            ${infoField(
                "Important Notes",
                item.notes
            )}
        `;
    }

    if (type === "tool") {

        content.innerHTML = `
            ${infoField(
                "Recommendation",
                item.description
            )}

            ${infoField(
                "Purpose",
                item.purpose
            )}

            ${infoField(
                "Usage",
                item.usage_info
            )}

            ${infoField(
                "Maintenance",
                item.maintenance
            )}

            ${infoField(
                "Important Notes",
                item.notes
            )}
        `;
    }
}

function infoField(title, value) {

    return `
        <div class="auto-info-row">

            <strong>
                ${escapeHTML(title)}
            </strong>

            <span>
                ${escapeHTML(value || "—")}
            </span>

        </div>
    `;
}

document
    .getElementById("addForm")
    .addEventListener("submit", async function(event) {

        event.preventDefault();

        const submitButton =
            document.getElementById("addSubmitButton");

        if (submitButton.disabled) {
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = "Adding...";

        const formData =
            new FormData(this);

        formData.append("action", "add");

        try {

            await requestJSON(
                API_URL,
                {
                    method: "POST",
                    body: formData
                }
            );

            this.reset();

            document.getElementById("itemType").value =
                currentCategory;

            loadCatalogOptions();

            closeAddModal();

            const refreshed =
                await loadInventory();

            if (!refreshed) {

                alert(
                    "The item was added, but the inventory list could not refresh."
                );
            }

        } catch (error) {

            console.error(error);

            alert(
                "Item could not be added.\n\n" +
                error.message
            );

        } finally {

            submitButton.disabled = false;
            submitButton.textContent = "Add Item";

        }
    });

async function changeStock(id, change) {

    id = Number(id);

    if (pendingStockUpdates.has(id)) {
        return;
    }

    pendingStockUpdates.add(id);

    renderInventory();

    const formData =
        new FormData();

    formData.append("action", "stock");
    formData.append("id", id);
    formData.append("change", change);

    try {

        await requestJSON(
            API_URL,
            {
                method: "POST",
                body: formData
            }
        );

        const refreshed =
            await loadInventory();

        if (!refreshed) {

            alert(
                "Stock was updated, but the inventory list could not refresh."
            );
        }

    } catch (error) {

        console.error(error);

        alert(
            "Stock could not be updated.\n\n" +
            error.message
        );

    } finally {

        pendingStockUpdates.delete(id);

        renderInventory();

    }
}

function showInfo(id) {

    const item =
        inventory.find(
            entry =>
                Number(entry.id) === Number(id)
        );

    if (!item) {
        return;
    }

    const type =
        item.item_type;

    const items =
        Array.isArray(catalog[type])
            ? catalog[type]
            : [];

    const catalogItem =
        items.find(
            entry =>
                String(entry.name).toLowerCase() ===
                String(item.name).toLowerCase()
        );

    document.getElementById("infoTitle").textContent =
        item.name;

    const image =
        getInventoryImage(item.image_path);

    let content = `

        <div class="details-top">

            <img
                class="details-image"
                src="${escapeHTML(image)}"
                alt="${escapeHTML(item.name)}"
                onerror="this.src='../images/verdexlogo.png'"
            >

            <div class="details-summary">

                <span class="type-badge">
                    ${
                        type === "plant"
                            ? "Plant"
                            : type === "fertilizer"
                                ? "Fertilizer"
                                : "Tool"
                    }
                </span>

                <h3>
                    ${escapeHTML(item.name)}
                </h3>

                <p>
                    Current stock:
                    <strong>
                        ${Number(item.stock)}
                        ${escapeHTML(item.unit)}
                    </strong>
                </p>

            </div>

        </div>

        <div class="details-section">

            <div class="details-section-title">
                Inventory Information
            </div>

    `;

    content += infoRow(
        "Current Stock",
        `${Number(item.stock)} ${item.unit}`
    );

    content += infoRow(
        "Status",
        Number(item.stock) <= 5
            ? "Low Stock"
            : "In Stock"
    );

    content += infoRow(
        "Date Added",
        formatDate(item.date_added)
    );

    content += infoRow(
        "Last Added",
        item.last_added
            ? formatDate(item.last_added)
            : "—"
    );

    content += infoRow(
        "Last Edited",
        item.updated_at
            ? formatDate(item.updated_at)
            : "—"
    );

    content += infoRow(
        "Notes",
        item.notes || "No notes."
    );

    content += `
        </div>
    `;

    if (catalogItem) {

        content += `

            <div class="details-section">

                <div class="details-section-title">
                    Item Information
                </div>

        `;

        if (type === "plant") {

            content += infoRow(
                "Description",
                catalogItem.description
            );

            content += infoRow(
                "Growing Method",
                catalogItem.growing_method
            );

            content += infoRow(
                "Growing Media",
                catalogItem.growing_media
            );

            content += infoRow(
                "Fertilizer",
                catalogItem.fertilizer_info
            );

            content += infoRow(
                "Harvest Time",
                catalogItem.harvest_time
            );

            content += infoRow(
                "Temperature",
                catalogItem.temperature_range
            );

            content += infoRow(
                "Humidity",
                catalogItem.humidity_range
            );

            content += infoRow(
                "Care",
                catalogItem.care
            );
        }

        if (type === "fertilizer") {

            content += infoRow(
                "Description",
                catalogItem.description
            );

            content += infoRow(
                "Nutrients",
                catalogItem.nutrient_info
            );

            content += infoRow(
                "Application",
                catalogItem.application_info
            );

            content += infoRow(
                "Suitable Plants",
                catalogItem.suitable_plants
            );

            content += infoRow(
                "Notes",
                catalogItem.notes
            );
        }

        if (type === "tool") {

            content += infoRow(
                "Description",
                catalogItem.description
            );

            content += infoRow(
                "Purpose",
                catalogItem.purpose
            );

            content += infoRow(
                "Usage",
                catalogItem.usage_info
            );

            content += infoRow(
                "Maintenance",
                catalogItem.maintenance
            );

            content += infoRow(
                "Notes",
                catalogItem.notes
            );
        }

        content += `
            </div>
        `;

        content += recommendation(
            item,
            catalogItem
        );
    }

    document.getElementById("infoContent").innerHTML =
        content;

    document
        .getElementById("infoModal")
        .classList.add("show");
}

function recommendation(item, catalogItem) {

    if (item.item_type === "plant") {

        return `
            <div class="ai-box">

                <div class="ai-title">
                    Recommendation
                </div>

                <div class="ai-text">
                    For ${escapeHTML(item.name)}, follow the
                    recommended growing method, nutrient guidance,
                    and care requirements. Monitor temperature,
                    humidity, water level, pH, and EC regularly.
                    Harvest timing should be based on plant condition
                    and actual growing conditions.
                </div>

            </div>
        `;
    }

    if (item.item_type === "fertilizer") {

        return `
            <div class="ai-box">

                <div class="ai-title">
                    Recommendation
                </div>

                <div class="ai-text">
                    Use ${escapeHTML(item.name)} only for plants
                    supported by the product guidance. Follow the
                    manufacturer's dosage and mixing instructions,
                    and monitor nutrient-solution EC and pH.
                </div>

            </div>
        `;
    }

    return `
        <div class="ai-box">

            <div class="ai-title">
                Recommendation
            </div>

            <div class="ai-text">
                Use ${escapeHTML(item.name)} according to its
                intended purpose. Follow proper cleaning,
                calibration, storage, and maintenance procedures
                when applicable.
            </div>

        </div>
    `;
}

function infoRow(title, value) {

    return `
        <div class="info-row">

            <strong>
                ${escapeHTML(title)}
            </strong>

            <span>
                ${escapeHTML(value || "—")}
            </span>

        </div>
    `;
}

function closeInfo() {

    document
        .getElementById("infoModal")
        .classList.remove("show");
}

function openNotes(id) {

    const item =
        inventory.find(
            entry => Number(entry.id) === Number(id)
        );

    if (!item) {
        return;
    }

    currentNoteId = Number(id);

    document.getElementById("notesInput").value =
        item.notes || "";

    document
        .getElementById("notesModal")
        .classList.add("show");
}

function closeNotes() {

    currentNoteId = null;

    document
        .getElementById("notesModal")
        .classList.remove("show");
}

async function saveNotes() {

    if (!currentNoteId) {
        return;
    }

    const notes =
        document.getElementById("notesInput").value;

    const formData =
        new FormData();

    formData.append("action", "notes");
    formData.append("id", currentNoteId);
    formData.append("notes", notes);

    try {

        await requestJSON(
            API_URL,
            {
                method: "POST",
                body: formData
            }
        );

        closeNotes();

        await loadInventory();

    } catch (error) {

        console.error(error);

        alert(
            "Notes could not be saved.\n\n" +
            error.message
        );
    }
}

function sortItems() {

    const sort =
        document.getElementById("sortSelect").value;

    const items =
        inventory.filter(
            item => item.item_type === currentCategory
        );

    if (sort === "newest") {

        items.sort(
            (a, b) =>
                new Date(b.date_added) -
                new Date(a.date_added)
        );

    } else if (sort === "oldest") {

        items.sort(
            (a, b) =>
                new Date(a.date_added) -
                new Date(b.date_added)
        );

    } else if (sort === "nameAsc") {

        items.sort(
            (a, b) =>
                a.name.localeCompare(b.name)
        );

    } else if (sort === "nameDesc") {

        items.sort(
            (a, b) =>
                b.name.localeCompare(a.name)
        );

    } else if (sort === "stockAsc") {

        items.sort(
            (a, b) =>
                Number(a.stock) -
                Number(b.stock)
        );

    } else if (sort === "stockDesc") {

        items.sort(
            (a, b) =>
                Number(b.stock) -
                Number(a.stock)
        );
    }

    renderSortedItems(items);
}

function renderSortedItems(items) {

    const container =
        document.getElementById("inventoryContainer");

    const empty =
        document.getElementById("emptyMessage");

    if (items.length === 0) {

        container.innerHTML = "";
        empty.style.display = "block";

        return;
    }

    empty.style.display = "none";

    container.innerHTML = items.map(item => {

        const image = item.image_path
            ? item.image_path
            : "../images/verdexlogo.png";

        const status =
            Number(item.stock) <= 5
                ? "low"
                : "good";

        const statusText =
            status === "low"
                ? "Low Stock"
                : "In Stock";

        return `
            <div class="inventory-card">

                <img
                    class="item-image"
                    src="${escapeHTML(image)}"
                    alt="${escapeHTML(item.name)}"
                    onerror="this.src='../images/verdexlogo.png'"
                >

                <div class="item-body">

                    <div class="item-type">
                        ${escapeHTML(item.item_type)}
                    </div>

                    <h2 class="item-name">
                        ${escapeHTML(item.name)}
                    </h2>

                    <span class="status ${status}">
                        ${statusText}
                    </span>

                    <div class="stock-row">

                        <span class="stock">
                            ${Number(item.stock)}
                            ${escapeHTML(item.unit)}
                        </span>

                        <div class="stock-buttons">

                            <button
                                onclick="changeStock(${item.id}, -1)">
                                −
                            </button>

                            <button
                                onclick="changeStock(${item.id}, 1)">
                                +
                            </button>

                        </div>

                    </div>

                    <div class="item-date">

                        Added:
                        ${formatDate(item.date_added)}

                        <br>

                        Last Added:
                        ${item.last_added
                            ? formatDate(item.last_added)
                            : "—"}

                        <br>

                        Last Edited:
                        ${item.updated_at
                            ? formatDate(item.updated_at)
                            : "—"}

                    </div>

                </div>

                <div class="card-actions">

                    <button
                        title="Information"
                        onclick="showInfo(${item.id})">
                        ⓘ
                    </button>

                    <button
                        title="Notes"
                        onclick="openNotes(${item.id})">
                        ✎
                    </button>

                </div>

            </div>
        `;

    }).join("");
}

function formatDate(date) {

    if (!date) {
        return "—";
    }

    const parsed =
        new Date(
            String(date).replace(" ", "T")
        );

    if (Number.isNaN(parsed.getTime())) {
        return date;
    }

    return parsed.toLocaleString(
        undefined,
        {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "2-digit"
        }
    );
}

function escapeHTML(value) {

    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}