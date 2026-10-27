// Pure operations and safe DOM rendering helpers.
export function filterSort(events, search, category, sort) {
  const q = search.trim().toLowerCase();
  const output = events.filter(e => (category === 'all' || e.category === category) &&
    [e.title, e.description, e.category].some(v => String(v).toLowerCase().includes(q)));
  if (sort === 'oldest') output.sort((a, b) => a.date.localeCompare(b.date));
  else if (sort === 'name') output.sort((a, b) => a.title.localeCompare(b.title));
  else if (sort === 'name-desc') output.sort((a, b) => b.title.localeCompare(a.title));
  else output.sort((a, b) => b.date.localeCompare(a.date));
  return output;
}
export function paginate(records, page, perPage) {
  return {items: records.slice((page - 1) * perPage, page * perPage), totalPages: Math.ceil(records.length / perPage)};
}
function tag(tagName, className, text) {
  const element = document.createElement(tagName);
  if (className) element.className = className;
  element.textContent = text;
  return element;
}
export function createEventCard(event) {
  const card = tag('article', 'event-card', '');
  const meta = tag('div', 'card-meta', '');
  meta.append(tag('span', 'tag', event.category));
  meta.append(tag('time', 'date', new Date(`${event.date}T00:00:00`).toLocaleDateString('en-IN', {day:'numeric', month:'short', year:'numeric'})));
  meta.lastChild.dateTime = event.date;
  card.append(meta, tag('h3', '', event.title), tag('p', '', event.description));
  return card;
}
export function createDatasetCard(record, type) {
  const card = tag('article', 'dataset-card', '');
  if (type === 'students') {
    card.append(tag('h3', '', record.name), tag('small', '', `${record.course} · Year ${record.year}`), tag('p', '', record.interest));
  } else {
    card.append(tag('h3', '', record.question), tag('p', '', record.answer), tag('small', '', record.category));
  }
  return card;
}
