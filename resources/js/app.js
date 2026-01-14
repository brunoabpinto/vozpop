import "./globals/theme.js"; /* By Sheaf.dev */

import {
  Livewire,
  Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
import intersect from "@alpinejs/intersect";

// now you can register
// components using Alpine.data(...) and
// plugins using Alpine.plugin(...)

Alpine.plugin(intersect);

Livewire.start();
