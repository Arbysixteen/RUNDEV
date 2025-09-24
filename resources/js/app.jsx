import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import RunDevApp from './components/RunDevApp';

// Initialize React app
const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<RunDevApp />);
}
