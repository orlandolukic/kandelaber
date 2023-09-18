import { createRoot } from 'react-dom/client';
import App from './App';

jQuery(window).on("load", function() {
    if (document.getElementById('product-category-listing')) { //check if element exists before rendering
        const root = createRoot(document.getElementById('product-category-listing'));
        root.render(<App />);
    }
});