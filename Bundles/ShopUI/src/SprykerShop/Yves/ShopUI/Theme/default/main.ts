declare const require;

import { bootstrap } from './Lib/app';

function importAll(requireContext) {
    requireContext.keys().forEach(requireContext);
}

import './Style/base';

importAll(require.context('./Component/Atom', true, /index\.ts$/));
importAll(require.context('./Component/Molecule', true, /index\.ts$/));
importAll(require.context('./Component/Organism', true, /index\.ts$/));
importAll(require.context('./Component/Template', true, /index\.ts$/));

import './Style/util';

document.addEventListener('DOMContentLoaded', bootstrap);
