/* Practical 4 — DOM selection, events, slider, FAQ, modal, theme, and responsive menu. */
'use strict';
(() => {
 const $ = (selector) => document.querySelector(selector);
 const themeButton = $('#p4Theme');
 const storageKey = 'studenthub-theme';
 let preferredTheme = 'light';
 try { preferredTheme = localStorage.getItem(storageKey) === 'dark' ? 'dark' : 'light'; } catch (_) {}
 function applyTheme(theme) {
   const dark = theme === 'dark';
   document.body.classList.toggle('p4-dark', dark);
   if (themeButton) {
     themeButton.textContent = dark ? '☀️ Light mode' : '🌙 Dark mode';
     themeButton.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
     themeButton.setAttribute('aria-pressed', String(dark));
   }
 }
 applyTheme(preferredTheme);
 themeButton?.addEventListener('click', () => {
   preferredTheme = document.body.classList.contains('p4-dark') ? 'light' : 'dark';
   applyTheme(preferredTheme);
   try { localStorage.setItem(storageKey, preferredTheme); } catch (_) {}
 });
 const menuButton = $('#p4Menu'), nav = $('#p4Navigation');
 function closeMenu() {nav?.classList.remove('p4-open'); menuButton?.setAttribute('aria-expanded', 'false');}
 menuButton?.addEventListener('click', () => {const open = nav.classList.toggle('p4-open');menuButton.setAttribute('aria-expanded',String(open));});
 nav?.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
 document.querySelectorAll('.p4-faq-question').forEach(button => {
   button.addEventListener('click', () => {
     const answer = document.getElementById(button.getAttribute('aria-controls'));
     if (!answer) return;
     const isOpen = button.getAttribute('aria-expanded') === 'true';
     button.setAttribute('aria-expanded', String(!isOpen));
     answer.hidden = isOpen;
     const indicator = button.querySelector('span'); if(indicator)indicator.textContent = isOpen ? '+' : '−';
   });
 });
 const notice = $('#p4Notice');
 $('#p4Dismiss')?.addEventListener('click', () => { if(notice) notice.hidden = true; });
 const slides = [
  {icon:'📚', title:'All your academics in one place', description:'Quickly access results, attendance, and your weekly timetable.'},
  {icon:'📅', title:'Stay on top of your schedule', description:'Keep track of weekly classes and upcoming college events.'},
  {icon:'🎓', title:'Follow your progress', description:'Monitor attendance and see your semester performance.'}
 ];
 let slideIndex = 0;
 function updateSlide() {
   if (!$('#p4SlideTitle'))return;
   const slide=slides[slideIndex];
   $('#p4SlideIcon').textContent=slide.icon;
   $('#p4SlideTitle').textContent=slide.title;
   $('#p4SlideText').textContent=slide.description;
   $('#p4SlideCounter').textContent=`${slideIndex+1} / ${slides.length}`;
   const region=$('.p4-slide-content');
   region.style.animation='none';void region.offsetWidth;region.style.animation='';
 }
 $('#p4Prev')?.addEventListener('click',()=>{slideIndex=(slideIndex-1+slides.length)%slides.length;updateSlide();});
 $('#p4Next')?.addEventListener('click',()=>{slideIndex=(slideIndex+1)%slides.length;updateSlide();});
 const modal=$('#p4Modal'), modalBox=modal?.querySelector('[role="dialog"]');
 let lastFocused=null;
 function closeModal(){if(!modal||modal.hidden)return;modal.hidden=true;document.body.classList.remove('p4-lock-scroll');lastFocused?.focus();}
 $('#p4OpenModal')?.addEventListener('click',()=>{if(!modal)return;lastFocused=document.activeElement;modal.hidden=false;document.body.classList.add('p4-lock-scroll');$('#p4CloseModal')?.focus();});
 $('#p4CloseModal')?.addEventListener('click',closeModal);
 $('#p4ModalOkay')?.addEventListener('click',closeModal);
 modal?.addEventListener('click',e=>{if(e.target===modal)closeModal();});
 document.addEventListener('keydown',e=>{
   if(e.key==='Escape'){closeModal();closeMenu();}
   if(e.key==='Tab' && modal && !modal.hidden){
     const focusable=[...modalBox.querySelectorAll('button,a[href],input,[tabindex]:not([tabindex="-1"])')].filter(el=>!el.disabled);
     if(!focusable.length)return;
     const first=focusable[0],last=focusable.at(-1);
     if(e.shiftKey && document.activeElement===first){e.preventDefault();last.focus();}
     else if(!e.shiftKey && document.activeElement===last){e.preventDefault();first.focus();}
   }
 });
})();
