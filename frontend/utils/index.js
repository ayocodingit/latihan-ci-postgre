/**
 * Get cookie from request.
 *
 * @param  {Object} req
 * @param  {String} key
 * @return {String|undefined}
 */
import moment from 'moment'
const locale = 'id'
moment.locale(locale)

export function cookieFromRequest(req, key) {
  if (!req.headers.cookie) {
    return
  }

  const cookie = req.headers.cookie.split(';').find(
    c => c.trim().startsWith(`${key}=`)
  )

  if (cookie) {
    return cookie.split('=')[1]
  }
}

/**
 * https://router.vuejs.org/en/advanced/scroll-behavior.html
 */
export function scrollBehavior(to, from, savedPosition) {
  if (savedPosition) {
    return savedPosition
  }

  let position = {}

  if (to.matched.length < 2) {
    position = {
      x: 0,
      y: 0
    }
  } else if (to.matched.some(r => r.components.default.options.scrollToTop)) {
    position = {
      x: 0,
      y: 0
    }
  }
  if (to.hash) {
    position = {
      selector: to.hash
    }
  }

  return position
}

// function to compare or search input with data array
export function toLowerStartsWith(input, dataArray) {
  if (input.length < 1) {
    return []
  }
  return dataArray.filter(data => {
    return data.toLowerCase()
      .startsWith(input.toLowerCase())
  })
}

// function to check data falsy
export function checkDataFalsy(data) {
  const newData = data && data !== '0' ? data : null;
  return newData;
}

// function to check data input time is valid
export function isFormatTimeValid(time) {
  return moment(time, "HH:mm", true).isValid();
}

// function to get swal attribute for time validation
export function getAlertTimeMessage(desc) {
  const alertMessage = {
    title: "Format Waktu Salah!",
    text: `Mohon input ${desc} dengan format 'HH:mm' dimana HH(jam) 00-23 dan mm(menit) 00-59`,
    confirmButtonText: 'OK',
    icon: "warning",
  };
  return alertMessage;
}

export function getAlertPopUp(type, content) {
  let alertMessage;
  if (type === 'delete') {
    alertMessage = {
      title: "Hapus Data",
      showCancelButton: true,
      confirmButtonText: `<i class='fa fa-trash' /> Ya, Hapus Data`,
      cancelButtonText: `<i class="fa fa-close" /> Tidak, Jangan Hapus`,
      icon: "warning",
      reverseButtons: true,
      html: content
    };
  } else if (type === 'invalid') {
    alertMessage = {
      title: "Apakah Anda Yakin Menandai Sampel Menjadi Invalid?",
      html: content,
      type: 'warning',
      input: 'text',
      showCancelButton: true,
      confirmButtonText: '<i class="uil-flask-potion"></i> Ya, Tandai Sampel Invalid',
      cancelButtonText: '<i class="fa fa-close"></i> Batalkan',
      reverseButtons: true
    };
  } else if (type === 'proses') {
    alertMessage = {
      title: 'Apakah Anda Yakin Mendandai Sampel untuk diproses?',
      html: content,
      type: 'info',
      input: 'text',
      showCancelButton: true,
      confirmButtonText: '<i class="uil-flask"></i> Ya, Tandai Sampel Proses',
      cancelButtonText: '<i class="fa fa-close"></i> Batalkan',
      reverseButtons: true
    };
  } else if (type === 'musnahkan') {
    alertMessage = {
      title: "Musnahkan Sampel",
      html: content,
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: '<i class="fa fa-trash"></i> Ya, Musnahkan Sampel',
      cancelButtonText: '<i class="fa fa-close"></i> Batalkan',
      reverseButtons: true
    };
  }else if (type === 'process-preview') {
    alertMessage = {
      title: 'Apakah anda yakin untuk memproses data berikut?',
      html: content,
      type: 'info',
      showCancelButton: true,
      confirmButtonText: '<i class="uil-flask"></i> Ya, Proses',
      cancelButtonText: '<i class="fa fa-close"></i> Batalkan',
      reverseButtons: true
    };
  }
  return alertMessage;
}

export function humanize(str) {
  var i, frags = str.split('_');
  for (i = 0; i < frags.length; i++) {
    frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
  }
  return frags.join(' ');
}

// function to get human age
export function getHumanAge(birthdDate, category) {
  const years = moment().diff(birthdDate, 'years');
  const months = moment().diff(birthdDate, 'months') % 12;
  if (category && category == 'tahun') {
    return `${years} tahun`;
  }
  return `${years} tahun ${months} bulan`;
}

// function to convert date use moment js
export function momentFormatDate(date) {
  return moment(date).format("D MMMM YYYY")
}

export function momentFormatDateYmd(date) {
  return moment(date).format("YYYY-MM-DD")
}

// function to convert time use moment js
export function momentFormatTime(time) {
  return moment(time).format("HH:mm:ss");
}

export function getDateIsoString(date) {
  if (date === null || new Date(date) === 'Invalid Date') {
    return null;
  }
  return new Date(date).toISOString();
}

export function getKeteranganData(nik, nama) {
  if ((nik == null || nik == '') || (nama == null || nama == '')) {
    return `
      <div class="text-red">
        <b>Data Belum Lengkap</b>
      </div>`;
  }
  if ((nik != null && nik != '') && (nama != null && nama != '')) {
    return `
      <div class="text-yellow">
        <b>Data Lengkap</b>
      </div>`;
  }
}
