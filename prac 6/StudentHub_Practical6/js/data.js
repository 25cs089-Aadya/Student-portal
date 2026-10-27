// Shared JSON loader used by the events and additional datasets.
export async function fetchJson(path) {
  const response = await fetch(path, { cache: 'no-store' });
  if (!response.ok) throw new Error(`${path} returned HTTP ${response.status}`);
  return response.json();
}
