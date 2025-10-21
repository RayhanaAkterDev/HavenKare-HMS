import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
	plugins: [tailwindcss(), autoprefixer()],
	build: {
		outDir: 'public', // optional if you want build output in 'public'
	},
})
