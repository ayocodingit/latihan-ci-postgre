<template>
  <nav class="navbar-default navbar-static-side bg-white" role="navigation">
    <div class="sidebar-collapse" v-if="user">
      <ul class="nav metismenu" id="side-menu">
        <li class="nav-header">
          <div class="dropdown profile-element">
            <img alt="image" class="img-fluid" src="~/assets/img/logo-lab-black.png" />
          </div>
          <div class="logo-element">
            <img alt="logo" src="@/assets/img/logo-lab.png" style="width:25px" /></div>
        </li>
        <li>
          <router-link to="/" tag="a">
            <em class="fa fa-home fa-fw" />
            <span class="nav-label">Dashboard</span>
          </router-link>
        </li>
        <li v-if="checkPermission('registrasi')">
          <a href="#">
            <em class="uil-user-square fa-fw" />
            <span class="nav-label">Registrasi</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/registrasi/mandiri" tag="a">
                Registrasi Mandiri (L)
              </router-link>
            </li>
            <li>
              <router-link to="/registrasi/rujukan" tag="a">
                Registrasi Rujukan (R)
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="checkPermission('sample')">
          <a href="#">
            <em class="uil-flask-potion fa-fw" />
            <span class="nav-label">Admin Sampel</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/sample" tag="a">
                Sampel Registrasi Mandiri
              </router-link>
            </li>
            <li>
              <router-link to="/sample/daftar" tag="a">
                Daftar Sampel
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="checkPermission('ekstraksi')">
          <a href="#">
            <em class="uil-flask fa-fw" />
            <span class="nav-label">Ekstraksi</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/ekstraksi/penerimaan" tag="a">
                Penerimaan Sampel
              </router-link>
            </li>
            <li>
              <router-link to="/ekstraksi" tag="a">
                Ekstraksi
              </router-link>
            </li>
            <li>
              <router-link to="/ekstraksi/terkirim" tag="a">
                Sampel Terkirim
              </router-link>
            </li>
            <li>
              <router-link to="/ekstraksi/dikembalikan" tag="a">
                Sampel Re-Ekstraksi
              </router-link>
            </li>

          </ul>
        </li>
        <li v-if="checkPermission('pcr')">
          <a href="#">
            <em class="uil-atom fa-fw" />
            <span class="nav-label">Pemeriksaan RT-PCR</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/pcr/list-rna" tag="a">
                Penerimaan RNA
              </router-link>
            </li>
            <li>
              <router-link to="/pcr/list-pcr" tag="a">
                List PCR
              </router-link>
            </li>
            <li>
              <router-link to="/pcr/list-hasil-pemeriksaan" tag="a">
                List Hasil Pemeriksaan
              </router-link>
            </li>
            <li>
              <router-link to="/pcr/list-hasil-inkonklusif" tag="a">
                Sampel Re-PCR
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="checkPermission('verifikasi')">
          <a href="#">
            <em class="uil-eye fa-fw" />
            <span class="nav-label">Verifikasi</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/verifikasi/list-unverified" tag="a">
                Menunggu Verifikasi
              </router-link>
            </li>
            <li>
              <router-link to="/verifikasi/list-verified" tag="a">
                Terverifikasi
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="checkPermission('validasi')">
          <a href="#">
            <em class="uil-check fa-fw" />
            <span class="nav-label">Validasi</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/validasi/list-unvalidate" tag="a">
                Menunggu Validasi
              </router-link>
            </li>
            <li>
              <router-link to="/validasi/list-validated" tag="a">
                Tervalidasi
              </router-link>
            </li>
          </ul>
        </li>
        <li>
          <router-link to="/pelacakan-sampel" tag="a">
            <em class="uil-search-alt fa-fw" />
            <span class="nav-label">Pelacakan Sampel</span>
          </router-link>
        </li>
        <li v-if="checkPermission('swab')">
          <a href="#">
            <em class="uil-dna fa-fw" />
            <span class="nav-label">Swab Antigen</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/swab-antigen/hasil-pemeriksaan" tag="a">
                Hasil Pemeriksaan
              </router-link>
            </li>
            <li v-if="checkPermission('validasi')">
              <router-link to="/swab-antigen/validated" tag="a">
                Tervalidasi
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="checkPermission('master')">
          <a href="#">
            <em class="uil-database-alt fa-fw" />
            <span class="nav-label">Data Master</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <nuxt-link to="/pengguna">Pengguna</nuxt-link>
            </li>
            <li>
              <nuxt-link to="/kota">Kota</nuxt-link>
            </li>
            <li>
              <nuxt-link to="/dinkes">Instansi Pengirim</nuxt-link>
            </li>
            <li>
              <nuxt-link to="/jenis-vtm">Jenis VTM</nuxt-link>
            </li>
            <li>
              <nuxt-link to="/reagen-ekstraksi">Reagen Ekstraksi</nuxt-link>
            </li>
            <li>
              <nuxt-link to="/reagen-pcr">Reagen PCR</nuxt-link>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
  import {
    mapGetters
  } from "vuex";

  export default {
    data: () => ({
      appName: process.env.appName,
    }),

    computed: mapGetters({
      user: "auth/user"
    }),

    methods: {
      checkPermission(menu) {
        let allow_role_id
        switch (menu) {
          case 'registrasi':
            allow_role_id = [1, 2]
            break;
          case 'sample':
            allow_role_id = [1, 3]
            break;
          case 'ekstraksi':
            allow_role_id = [1, 4]
            break;
          case 'pcr':
            allow_role_id = [1, 5]
            break;
          case 'verifikasi':
            allow_role_id = [1, 6]
            break;
          case 'validasi':
            allow_role_id = [1, 7]
            break;
          case 'swab':
            allow_role_id = [1, 2, 7]
            break;
          case 'master':
            allow_role_id = [1]
            break;
        }
        if (this.user && this.user.role_id) {
          return Array.isArray(allow_role_id) ? allow_role_id.includes(this.user.role_id) : []
        }
        return
      },
    },
    mounted() {
      this.$nextTick(function () {
        // Fast fix bor position issue with Propper.js
        // Will be fixed in Bootstrap 4.1 - https://github.com/twbs/bootstrap/pull/24092
        Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false;

        // Add body-small class if window less than 768px
        if (window.innerWidth < 769) {
          $("body").addClass("body-small");
        } else {
          $("body").removeClass("body-small");
        }

        // MetisMenu
        if (window && window.$ && window.$('#side-menu').metisMenu) {
          var sideMenu = window.$('#side-menu').metisMenu();
        }

        // Move right sidebar top after scroll
        $(window).scroll(function () {
          if ($(window).scrollTop() > 0 && !$("body").hasClass("fixed-nav")) {
            $("#right-sidebar").addClass("sidebar-top");
          } else {
            $("#right-sidebar").removeClass("sidebar-top");
          }
        });

        // Add slimscroll to element
        // $('.full-height-scroll').slimscroll({
        //     height: '100%'
        // })
      });

      // Minimalize menu when screen is less than 768px
      $(window).bind("resize", function () {
        if (window.innerWidth < 769) {
          $("body").addClass("body-small");
        } else {
          $("body").removeClass("body-small");
        }
      });

      // Fixed Sidebar
      $(window).bind("load", function () {
        if ($("body").hasClass("fixed-sidebar")) {
          // $('.sidebar-collapse').slimScroll({
          //     height: '100%',
          //     railOpacity: 0.9
          // });
        }
      });

      // check if browser support HTML5 local storage
      function localStorageSupport() {
        return "localStorage" in window && window["localStorage"] !== null;
      }

      // Local Storage functions
      // Set proper body class and plugins based on user configuration
      $(document).ready(function () {
        if (localStorageSupport()) {
          var collapse = localStorage.getItem("collapse_menu");
          var fixedsidebar = localStorage.getItem("fixedsidebar");
          var fixednavbar = localStorage.getItem("fixednavbar");
          var boxedlayout = localStorage.getItem("boxedlayout");
          var fixedfooter = localStorage.getItem("fixedfooter");

          var body = $("body");

          if (fixedsidebar == "on") {
            body.addClass("fixed-sidebar");
            // $('.sidebar-collapse').slimScroll({
            //     height: '100%',
            //     railOpacity: 0.9
            // });
          }

          if (collapse == "on") {
            if (body.hasClass("fixed-sidebar") || !body.hasClass("body-small")) {
                body.addClass("mini-navbar");
              }
            }
          }

          if (fixednavbar == "on") {
            $(".navbar-static-top")
              .removeClass("navbar-static-top")
              .addClass("navbar-fixed-top");
            body.addClass("fixed-nav");
          }

          if (boxedlayout == "on") {
            body.addClass("boxed-layout");
          }

          if (fixedfooter == "on") {
            $(".footer").addClass("fixed");
          }
        }
      );

      // For demo purpose - animation css script
      function animationHover(element, animation) {
        element = $(element);
        element.hover(
          function () {
            element.addClass("animated " + animation);
          },
          function () {
            //wait for animation to finish before removing classes
            window.setTimeout(function () {
              element.removeClass("animated " + animation);
            }, 2000);
          }
        );
      }

      function SmoothlyMenu() {
        if (
          !$("body").hasClass("mini-navbar") ||
          $("body").hasClass("body-small")
        ) {
          // Hide menu in order to smoothly turn on when maximize menu
          $("#side-menu").hide();
          // For smoothly turn on menu
          setTimeout(function () {
            $("#side-menu").fadeIn(400);
          }, 200);
        } else if ($("body").hasClass("fixed-sidebar")) {
          $("#side-menu").hide();
          setTimeout(function () {
            $("#side-menu").fadeIn(400);
          }, 100);
        } else {
          // Remove all inline style from jquery fadeIn function to reset menu state
          $("#side-menu").removeAttr("style");
        }
      }

      // Dragable panels
      function WinMove() {
        var element = "[class*=col]";
        var handle = ".ibox-title";
        var connect = "[class*=col]";
        $(element)
          .sortable({
            handle: handle,
            connectWith: connect,
            tolerance: "pointer",
            forcePlaceholderSize: true,
            opacity: 0.8
          })
          .disableSelection();
      }
      // END MOUNTED
    }
  };
</script>

<style scoped>
  .profile-photo {
    width: 2rem;
    height: 2rem;
    margin: -0.375rem 0;
  }
</style>