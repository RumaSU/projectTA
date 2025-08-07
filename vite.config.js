import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

function getFilesFromDir(dir, ext = '.js') {
    const dirPath = path.resolve(__dirname, dir);

    return fs.readdirSync(dirPath)
        .filter(file => file.endsWith(ext))
        .map(file => `${dir}/${file}`);
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/events/documents/main.js',
                
                
                'resources/js/events/processDocuments.js',
                'resources/js/pdf-viewer.js'
                // 'resources/js/events/processDocuments.js'
                // ...getFilesFromDir("resources/js/events"),
            ],
            // refresh: true,
            refresh: false,
        }),
    ],
});
