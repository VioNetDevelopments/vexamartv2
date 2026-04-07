/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Wajib untuk toggle manual
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Premium Navy Theme
                navy: {
                    50: '#F8FAFC',
                    100: '#F1F5F9',
                    800: '#1E293B', // Secondary Navy
                    900: '#0F172A', // Primary Navy (Sidebar/Topbar)
                    950: '#0B1120', // Background Dark
                },
                accent: {
                    500: '#2563EB', // Accent Blue
                    600: '#1D4ED8',
                    hover: '#3B82F6', // Soft Blue Hover
                },
                success: '#16A34A',
                danger: '#DC2626',
                warning: '#F59E0B',
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'], // Font modern
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                'glow': '0 0 15px rgba(37, 99, 235, 0.3)',
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}