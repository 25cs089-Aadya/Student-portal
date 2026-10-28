// Practical 13 - Simple Fetch API CRUD
const form = document.getElementById("eventForm");
const eventsBox = document.getElementById("events");
const messageBox = document.getElementById("message");
const loading = document.getElementById("loading");
const saveButton = document.getElementById("saveButton");
const cancelButton = document.getElementById("cancelButton");
let token = "";
let editingId = null;
let events = [];

function showMessage(text, type = "success") {
  messageBox.textContent = text;
  messageBox.className = type;
}

async function callApi(method = "GET", data = null) {
  const options = { method, headers: { Accept: "application/json" } };
  if (data !== null) {
    options.headers["Content-Type"] = "application/json";
    options.headers["X-CSRF-Token"] = token;
    options.body = JSON.stringify(data);
  }
  const response = await fetch("api.php", options);
  const result = await response.json();
  if (!response.ok || !result.success) {
    throw new Error(result.message || "Request failed (" + response.status + ")");
  }
  return result;
}

function addText(parent, tag, text) {
  const element = document.createElement(tag);
  element.textContent = text;
  parent.appendChild(element);
}

function showEvents() {
  eventsBox.replaceChildren();
  if (events.length === 0) {
    addText(eventsBox, "p", "No events available.");
    return;
  }
  for (const event of events) {
    const card = document.createElement("div");
    card.className = "event";
    addText(card, "h3", event.event_name);
    addText(card, "p", "Date: " + event.event_date);
    addText(card, "p", "Venue: " + event.venue);
    addText(card, "p", "Status: " + event.status);
    addText(card, "p", event.description || "");
    const edit = document.createElement("button");
    edit.textContent = "Edit";
    edit.onclick = () => startEdit(event);
    card.appendChild(edit);
    const del = document.createElement("button");
    del.textContent = "Delete";
    del.className = "delete";
    del.onclick = () => deleteEvent(event.event_id);
    card.appendChild(del);
    eventsBox.appendChild(card);
  }
}

async function loadEvents() {
  loading.hidden = false;
  loading.textContent = "Loading events...";
  try {
    const result = await callApi();
    token = result.csrf_token;
    events = result.data;
    showEvents();
    loading.hidden = true;
  } catch (error) {
    loading.textContent = "Unable to load events.";
    showMessage(error.message, "error");
  }
}

function resetForm() {
  form.reset();
  editingId = null;
  document.getElementById("formTitle").textContent = "Add New Event";
  saveButton.textContent = "Add Event";
  cancelButton.hidden = true;
}

function startEdit(event) {
  editingId = Number(event.event_id);
  document.getElementById("event_name").value = event.event_name;
  document.getElementById("event_date").value = event.event_date.replace(" ", "T").slice(0, 16);
  document.getElementById("venue").value = event.venue;
  document.getElementById("status").value = event.status;
  document.getElementById("description").value = event.description || "";
  document.getElementById("formTitle").textContent = "Edit Event";
  saveButton.textContent = "Update Event";
  cancelButton.hidden = false;
  window.scrollTo(0, 0);
}

form.addEventListener("submit", async function (event) {
  event.preventDefault(); // No page reload
  const data = {
    event_name: document.getElementById("event_name").value.trim(),
    event_date: document.getElementById("event_date").value,
    venue: document.getElementById("venue").value.trim(),
    status: document.getElementById("status").value,
    description: document.getElementById("description").value.trim()
  };
  if (!data.event_name || !data.event_date || !data.venue || !data.description) {
    showMessage("Please complete all fields.", "error");
    return;
  }
  saveButton.disabled = true;
  const method = editingId === null ? "POST" : "PUT";
  if (editingId !== null) data.event_id = editingId;
  try {
    const result = await callApi(method, data);
    resetForm();
    await loadEvents();
    showMessage(result.message);
  } catch (error) {
    showMessage(error.message, "error");
  } finally {
    saveButton.disabled = false;
  }
});

async function deleteEvent(id) {
  if (!confirm("Delete this event?")) return;
  try {
    const result = await callApi("DELETE", { event_id: Number(id) });
    await loadEvents();
    showMessage(result.message);
  } catch (error) {
    showMessage(error.message, "error");
  }
}

cancelButton.onclick = resetForm;
document.getElementById("refreshButton").onclick = loadEvents;
loadEvents();
