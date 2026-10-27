// StudentHub Practical 6: Fetch API + search/filter/sort/pagination, modular JavaScript.
import { fetchJson } from './data.js';
import { filterSort, paginate, createEventCard, createDatasetCard } from './ui.js';

const $ = (id) => document.getElementById(id);
const state = { events: [], students: [], faqs: [], page: 1, perPage: 5, dataset: 'students' };
const search = $('searchInput');
const category = $('categoryFilter');
const sort = $('sortSelect');

function filtered() {
  return filterSort(state.events, search.value, category.value, sort.value);
}
function render() {
  const results = filtered();
  const { items, totalPages } = paginate(results, state.page, state.perPage);
  $('resultCount').textContent = `Showing ${items.length ? (state.page - 1) * state.perPage + 1 : 0}–${Math.min(state.page * state.perPage, results.length)} of ${results.length} events`;
  const container = $('eventContainer');
  container.replaceChildren();
  if (!items.length) {
    const empty = document.createElement('p');
    empty.className = 'state';
    empty.textContent = 'No events found. Try another search or category.';
    container.appendChild(empty);
  }
  for (const event of items) container.appendChild(createEventCard(event));
  const nav = $('pagination');
  nav.replaceChildren();
  if (totalPages <= 1) return;
  const addPageButton = (label, page, disabled = false, active = false) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = label;
    button.disabled = disabled;
    button.classList.toggle('active', active);
    if (active) button.setAttribute('aria-current', 'page');
    button.addEventListener('click', () => { state.page = page; render(); $('exploreTitle').scrollIntoView({ behavior: 'smooth' }); });
    nav.appendChild(button);
  };
  addPageButton('‹ Prev', state.page - 1, state.page === 1);
  for (let i = 1; i <= totalPages; i++) addPageButton(String(i), i, false, i === state.page);
  addPageButton('Next ›', state.page + 1, state.page === totalPages);
}
function populateCategories() {
  category.replaceChildren(new Option('All categories', 'all'));
  const names = [...new Set(state.events.map((event) => event.category))].sort();
  for (const name of names) category.add(new Option(name, name));
  $('totalCount').textContent = String(state.events.length);
  $('categoryCount').textContent = String(names.length);
}
async function loadEvents() {
  $('loading').hidden = false;
  $('error').hidden = true;
  $('eventContainer').replaceChildren();
  $('pagination').replaceChildren();
  $('resultCount').textContent = 'Loading event records…';
  try {
    // Small delay makes the loading indicator visible for screenshots.
    const [data] = await Promise.all([fetchJson('data/events.json'), new Promise((resolve) => setTimeout(resolve, 700))]);
    state.events = data;
    if (!Array.isArray(data) || data.some(e => !e.title || !e.category || !e.date)) throw new Error('Unexpected event data format');
    state.page = 1;
    populateCategories();
    render();
  } catch (err) {
    state.events = [];
    $('totalCount').textContent = '—';
    $('categoryCount').textContent = '—';
    $('resultCount').textContent = 'Unable to display events';
    $('error').textContent = `Error loading events: ${err.message}. Check that Live Server is running and data/events.json exists.`;
    $('error').hidden = false;
    console.error(err);
  } finally { $('loading').hidden = true; }
}
function renderDataset() {
  const list = state[state.dataset];
  const container = $('datasetContainer');
  container.replaceChildren();
  for (const item of list) container.appendChild(createDatasetCard(item, state.dataset));
}
async function loadDatasets() {
  $('datasetError').hidden = true;
  $('datasetLoading').hidden = false;
  try {
    const [students, faqs] = await Promise.all([fetchJson('data/students.json'), fetchJson('data/faqs.json')]);
    if (!Array.isArray(students) || !Array.isArray(faqs)) throw new Error('Unexpected dataset format');
    state.students = students;
    state.faqs = faqs;
    renderDataset();
  } catch (err) {
    $('datasetError').textContent = `Unable to load student/FAQ records: ${err.message}`;
    $('datasetError').hidden = false;
    console.error(err);
  } finally { $('datasetLoading').hidden = true; }
}
for (const element of [search, category, sort]) element.addEventListener(element === search ? 'input' : 'change', () => { state.page = 1; render(); });
$('clearFilters').addEventListener('click', () => { search.value = ''; category.value = 'all'; sort.value = 'newest'; state.page = 1; render(); });
$('reloadButton').addEventListener('click', () => { void loadEvents(); void loadDatasets(); });
document.querySelectorAll('.dataset-tab').forEach(button => button.addEventListener('click', () => {
  state.dataset = button.dataset.dataset;
  document.querySelectorAll('.dataset-tab').forEach(tab => {
    const active = tab === button;
    tab.classList.toggle('active', active);
    tab.setAttribute('aria-pressed', String(active));
  });
  renderDataset();
}));
$('themeToggle').addEventListener('click', () => {
  const dark = document.body.classList.toggle('dark');
  $('themeToggle').textContent = dark ? '☀ Light mode' : '☾ Dark mode';
  $('themeToggle').setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
});
void loadEvents();
void loadDatasets();
