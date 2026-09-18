import { test } from 'node:test';
import assert from 'node:assert/strict';

// Guard untuk bug urutan deklarasi di level atas modul (mis. TDZ pada
// `const TABS` yang dibaca `initialTab()` saat `state` diinisialisasi).
// Bug semacam ini tidak terlihat oleh test PHP (Blade hanya render shell),
// tetapi membuat bundle gagal dievaluasi sehingga #jara-app kosong.
const modules = ['mock.js', 'store.js', 'collaboration.js', 'ui.js'];

for (const file of modules) {
    test(`resources/js/jara/${file} dapat dievaluasi tanpa error inisialisasi`, async () => {
        await assert.doesNotReject(
            () => import(`../../resources/js/jara/${file}`),
            ReferenceError,
        );
    });
}
