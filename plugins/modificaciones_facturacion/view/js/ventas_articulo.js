/*
 * This file is part of facturacion_base
 * Copyright (C) 2014-2017  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$(document).ready(function () {

    if (window.location.hash.substring(1) == 'precios') {
        $('#tab_articulo a[href="#precios"]').tab('show');
    } else if (window.location.hash.substring(1) == 'stock') {
        $('#tab_articulo a[href="#stock"]').tab('show');
    } else if (window.location.hash.substring(1) == 'atributos') {
        $('#tab_articulo a[href="#atributos"]').tab('show');
    }

    $("#b_imagen").click(function (event) {
        event.preventDefault();
        $("#modal_articulo_imagen").modal('show');
    });
});