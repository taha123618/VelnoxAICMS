const fs = require('fs');

const typeIcons = {
    'section': 'ph:layout-bold',
    'container': 'ph:square-bold',
    'flexbox': 'ph:rectangle-dashed-fill',
    'grid': 'ph:grid-four-fill',
    'heading': 'ph:text-h',
    'paragraph': 'ph:text-paragraph',
    'button': 'ph:hand-pointing-fill',
    'image': 'ph:image-square-fill',
    'badge': 'ph:seal-check-fill',
    'icon': 'ph:star-fill',
    'form': 'ph:textbox-fill',
    'form-input': 'ph:textbox-fill',
    'form-textarea': 'ph:textbox-fill'
};

function fixIcons(node) {
    if (!node.icon) {
        node.icon = typeIcons[node.type] || 'ph:circle-fill';
    }
    if (node.children && Array.isArray(node.children)) {
        node.children.forEach(fixIcons);
    }
}

const dir = 'Modules/Builder/resources/blocks/modern/';
const files = fs.readdirSync(dir).filter(f => f.endsWith('.json'));

for (const file of files) {
    const path = dir + file;
    const data = JSON.parse(fs.readFileSync(path));
    const innerData = typeof data.data === 'string' ? JSON.parse(data.data) : data.data;
    fixIcons(innerData);
    data.data = JSON.stringify(innerData);
    fs.writeFileSync(path, JSON.stringify(data, null, 2));
}

console.log('Icons fixed!');
