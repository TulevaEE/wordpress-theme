const fs = require('fs');
const path = require('path');

const THEME_DIR = path.join(__dirname, '..');
const SKIPPED_DIRS = new Set(['node_modules', 'vendor', 'tests', 'css', 'scss', 'lang', 'js']);

function phpFiles(dir) {
    return fs.readdirSync(dir, { withFileTypes: true }).flatMap((entry) => {
        const entryPath = path.join(dir, entry.name);
        if (entry.isDirectory()) {
            return SKIPPED_DIRS.has(entry.name) ? [] : phpFiles(entryPath);
        }
        return entry.name.endsWith('.php') ? [entryPath] : [];
    });
}

function linksOpeningANewTab(file) {
    return fs
        .readFileSync(file, 'utf8')
        .split('\n')
        .map((line, index) => ({ line, number: index + 1 }))
        .filter(({ line }) => /target=["']_blank["']/.test(line) && !line.includes('<form'))
        .map(({ number }) => `${path.relative(THEME_DIR, file)}:${number}`);
}

describe('theme links', () => {
    it('open in the same tab, so the back button works and phones do not pile up tabs', () => {
        expect(phpFiles(THEME_DIR).flatMap(linksOpeningANewTab)).toEqual([]);
    });
});
