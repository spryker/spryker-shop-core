declare const require;

import { bootstrap } from './app';

function importAll(requireContext) {
    requireContext.keys().forEach(requireContext);
}

import './styles/basics';

importAll(require.context('./components/atoms', true, /index\.ts$/));
importAll(require.context('./components/molecules', true, /index\.ts$/));
importAll(require.context('./components/organisms', true, /index\.ts$/));
importAll(require.context('./components/templates', true, /index\.ts$/));

import './styles/utils';

document.addEventListener('DOMContentLoaded', bootstrap);
