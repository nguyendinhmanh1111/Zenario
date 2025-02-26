<?php
/*
 * Copyright (c) 2023, Tribal Limited
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Zenario, Tribal Limited nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL TRIBAL LTD BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
if (!defined('NOT_ACCESSED_DIRECTLY')) exit('This file may not be directly accessed');


//Warning:
	//This update will always be run with each update; no matter what the version numbers are!


//This update will create and/or update the list of document types used for Zenario
 ze\dbAdm::revision( null
, <<<_sql
	INSERT INTO `[[DB_PREFIX]]document_types`
		(`type`, `mime_type`, `custom`)
	VALUES
		('323', 'text/h323', 0),
		('7z', 'application/x-7z-compressed', 0),
		('acx', 'application/internet-property-stream', 0),
		('ai', 'application/postscript', 0),
		('aif', 'audio/x-aiff', 0),
		('aifc', 'audio/x-aiff', 0),
		('aiff', 'audio/x-aiff', 0),
		('asc', 'text/plain', 0),
		('asf', 'video/x-ms-asf', 0),
		('asr', 'video/x-ms-asf', 0),
		('asx', 'video/x-ms-asf', 0),
		('au', 'audio/basic', 0),
		('avi', 'video/x-msvideo', 0),
		('axs', 'application/olescript', 0),
		('bas', 'text/plain', 0),
		('bcpio', 'application/x-bcpio', 0),
		('bmp', 'image/bmp', 0),
		('c', 'text/plain', 0),
		('cat', 'application/vnd.ms-pkiseccat', 0),
		('cdf', 'application/x-cdf', 0),
		('cer', 'application/x-x509-ca-cert', 0),
		('class', 'application/octet-stream', 0),
		('clp', 'application/x-msclip', 0),
		('cmx', 'image/x-cmx', 0),
		('cod', 'image/cis-cod', 0),
		('cpio', 'application/x-cpio', 0),
		('crd', 'application/x-mscardfile', 0),
		('crl', 'application/pkix-crl', 0),
		('crt', 'application/x-x509-ca-cert', 0),
		('csh', 'application/x-csh', 0),
		('css', 'text/css', 0),
		('csv', 'text/csv', 0),
		('dcr', 'application/x-director', 0),
		('der', 'application/x-x509-ca-cert', 0),
		('dir', 'application/x-director', 0),
		('dll', 'application/x-msdownload', 0),
		('dms', 'application/octet-stream', 0),
		('doc', 'application/msword', 0),
		('docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 0),
		('dot', 'application/msword', 0),
		('dotx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.template', 0),
		('dvi', 'application/x-dvi', 0),
		('dwg', 'application/acad', 0),
		('dxr', 'application/x-director', 0),
		('eps', 'application/postscript', 0),
		('etx', 'text/x-setext', 0),
		('evy', 'application/envoy', 0),
		('fif', 'application/fractals', 0),
		('flr', 'x-world/x-vrml', 0),
		('flv', 'video/x-flv', 0),
		('gif', 'image/gif', 0),
		('gtar', 'application/x-gtar', 0),
		('gz', 'application/x-gzip', 0),
		('h', 'text/plain', 0),
		('hdf', 'application/x-hdf', 0),
		('hlp', 'application/winhlp', 0),
		('hqx', 'application/mac-binhex40', 0),
		('hta', 'application/hta', 0),
		('htc', 'text/x-component', 0),
		('ico', 'image/x-icon', 0),
		('ics', 'text/calendar', 0),
		('ief', 'image/ief', 0),
		('iii', 'application/x-iphone', 0),
		('ins', 'application/x-internet-signup', 0),
		('isp', 'application/x-internet-signup', 0),
		('jfif', 'image/pipeg', 0),
		('jpe', 'image/jpeg', 0),
		('jpeg', 'image/jpeg', 0),
		('jpg', 'image/jpeg', 0),
		('json', 'application/json', 0),
		('latex', 'application/x-latex', 0),
		('lha', 'application/octet-stream', 0),
		('lsf', 'video/x-la-asf', 0),
		('lsx', 'video/x-la-asf', 0),
		('lzh', 'application/octet-stream', 0),
		('m13', 'application/x-msmediaview', 0),
		('m14', 'application/x-msmediaview', 0),
		('m3u', 'audio/x-mpegurl', 0),
		('m4v', 'video/x-m4v', 0),
		('man', 'application/x-troff-man', 0),
		('md', 'text/markdown', 0),
		('mdb', 'application/x-msaccess', 0),
		('me', 'application/x-troff-me', 0),
		('mht', 'message/rfc822', 0),
		('mid', 'audio/mid', 0),
		('midi', 'audio/mid', 0),
		('mny', 'application/x-msmoney', 0),
		('mov', 'video/quicktime', 0),
		('movie', 'video/x-sgi-movie', 0),
		('mp2', 'video/mpeg', 0),
		('mp3', 'audio/mpeg', 0),
		('mp4', 'video/mp4', 0),
		('mpa', 'video/mpeg', 0),
		('mpe', 'video/mpeg', 0),
		('mpeg', 'video/mpeg', 0),
		('mpg', 'video/mpeg', 0),
		('mpp', 'application/vnd.ms-project', 0),
		('mpv2', 'video/mpeg', 0),
		('ms', 'application/x-troff-ms', 0),
		('msg', 'application/vnd.ms-outlook', 0),
		('mvb', 'application/x-msmediaview', 0),
		('nc', 'application/x-netcdf', 0),
		('nws', 'message/rfc822', 0),
		('oda', 'application/oda', 0),
		('ods', 'application/vnd.oasis.opendocument.spreadsheet', 0),
		('odt', 'application/vnd.oasis.opendocument.text', 0),
		('oga', 'application/ogg', 0),
		('ogg', 'audio/ogg', 0),
		('ogv', 'video/ogg', 0),
		('p10', 'application/pkcs10', 0),
		('p12', 'application/x-pkcs12', 0),
		('p7b', 'application/x-pkcs7-certificates', 0),
		('p7c', 'application/x-pkcs7-mime', 0),
		('p7m', 'application/x-pkcs7-mime', 0),
		('p7r', 'application/x-pkcs7-certreqresp', 0),
		('p7s', 'application/x-pkcs7-signature', 0),
		('pbm', 'image/x-portable-bitmap', 0),
		('pdf', 'application/pdf', 0),
		('pfx', 'application/x-pkcs12', 0),
		('pgm', 'image/x-portable-graymap', 0),
		('pko', 'application/ynd.ms-pkipko', 0),
		('pma', 'application/x-perfmon', 0),
		('pmc', 'application/x-perfmon', 0),
		('pml', 'application/x-perfmon', 0),
		('pmr', 'application/x-perfmon', 0),
		('pmw', 'application/x-perfmon', 0),
		('png', 'image/png', 0),
		('pnm', 'image/x-portable-anymap', 0),
		('pot', 'application/vnd.ms-powerpoint', 0),
		('potx', 'application/vnd.openxmlformats-officedocument.presentationml.template', 0),
		('ppm', 'image/x-portable-pixmap', 0),
		('pps', 'application/vnd.ms-powerpoint', 0),
		('ppsx', 'application/vnd.openxmlformats-officedocument.presentationml.slideshow', 0),
		('ppt', 'application/vnd.ms-powerpoint', 0),
		('pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 0),
		('prf', 'application/pics-rules', 0),
		('ps', 'application/postscript', 0),
		('psd', 'application/octet-stream', 0),
		('pub', 'application/x-mspublisher', 0),
		('qt', 'video/quicktime', 0),
		('ra', 'audio/vnd.rn-realaudio', 0),
		('ram', 'audio/vnd.rn-realaudio', 0),
		('ras', 'image/x-cmu-raster', 0),
		('rgb', 'image/x-rgb', 0),
		('rm', 'application/vnd.rn-realmedia', 0),
		('rmi', 'audio/mid', 0),
		('roff', 'application/x-troff', 0),
		('rpm', 'audio/x-pn-realaudio-plugin', 0),
		('rtf', 'application/rtf', 0),
		('rtx', 'text/richtext', 0),
		('rv', 'video/vnd.rn-realvideo', 0),
		('rvt', 'application/octet-stream', 0),
		('scd', 'application/x-msschedule', 0),
		('sct', 'text/scriptlet', 0),
		('setpay', 'application/set-payment-initiation', 0),
		('setreg', 'application/set-registration-initiation', 0),
		('shar', 'application/x-shar', 0),
		('sit', 'application/x-stuffit', 0),
		('sldx', 'application/vnd.openxmlformats-officedocument.presentationml.slide', 0),
		('snd', 'audio/basic', 0),
		('spc', 'application/x-pkcs7-certificates', 0),
		('spl', 'application/futuresplash', 0),
		('src', 'application/x-wais-source', 0),
		('sst', 'application/vnd.ms-pkicertstore', 0),
		('stl', 'application/vnd.ms-pkistl', 0),
		('sv4cpio', 'application/x-sv4cpio', 0),
		('sv4crc', 'application/x-sv4crc', 0),
		('svg', 'image/svg+xml', 0),
		('swf', 'application/x-shockwave-flash', 0),
		('t', 'application/x-troff', 0),
		('tar', 'application/x-tar', 0),
		('tcl', 'application/x-tcl', 0),
		('tex', 'application/x-tex', 0),
		('texi', 'application/x-texinfo', 0),
		('texinfo', 'application/x-texinfo', 0),
		('tgz', 'application/x-compressed', 0),
		('tif', 'image/tiff', 0),
		('tiff', 'image/tiff', 0),
		('tr', 'application/x-troff', 0),
		('trm', 'application/x-msterminal', 0),
		('tsv', 'text/tab-separated-values', 0),
		('txt', 'text/plain', 0),
		('uls', 'text/iuls', 0),
		('unknown', 'application/octet-stream', 0),
		('ustar', 'application/x-ustar', 0),
		('vcf', 'text/x-vcard', 0),
		('vrml', 'x-world/x-vrml', 0),
		('vsd', 'application/x-visio', 0),
		('wav', 'audio/x-wav', 0),
		('wcm', 'application/vnd.ms-works', 0),
		('wdb', 'application/vnd.ms-works', 0),
		('wks', 'application/vnd.ms-works', 0),
		('wmf', 'application/x-msmetafile', 0),
		('wmv', 'audio/x-ms-wmv', 0),
		('wps', 'application/vnd.ms-works', 0),
		('wri', 'application/x-mswrite', 0),
		('wrl', 'x-world/x-vrml', 0),
		('wrz', 'x-world/x-vrml', 0),
		('xaf', 'x-world/x-vrml', 0),
		('xbm', 'image/x-xbitmap', 0),
		('xla', 'application/vnd.ms-excel', 0),
		('xlam', 'application/vnd.ms-excel.addin.macroEnabled.12', 0),
		('xlc', 'application/vnd.ms-excel', 0),
		('xlm', 'application/vnd.ms-excel', 0),
		('xls', 'application/vnd.ms-excel', 0),
		('xlsb', 'application/vnd.ms-excel.sheet.binary.macroEnabled.12', 0),
		('xlsm', 'application/vnd.ms-excel.sheet.macroEnabled.12', 0),
		('xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 0),
		('xlt', 'application/vnd.ms-excel', 0),
		('xltx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.template', 0),
		('xlw', 'application/vnd.ms-excel', 0),
		('xml', 'text/xml', 0),
		('xof', 'x-world/x-vrml', 0),
		('xpm', 'image/x-xpixmap', 0),
		('xwd', 'image/x-xwindowdump', 0),
		('z', 'application/x-compress', 0),
		('zip', 'application/zip', 0)
	ON DUPLICATE KEY UPDATE
		mime_type = VALUES(mime_type),
		custom = 0
_sql
 );
