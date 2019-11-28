/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 *
*/

var velovalidation = {
    language: {},
    script_regex: /(<script[\s\S]*?>[\s\S]*?<\/script>)|(<script[\s\S]*?>)|([\s\S]*?<\/script>)/i,
    style_regex: /(<style[\s\S]*?>[\s\S]*?<\/style>)|(<style[\s\S]*?>)|([\s\S]*?<\/style>)/i,
    iframe_regex: /(<iframe[\s\S]*?>[\s\S]*?<\/iframe>)|(<iframe[\s\S]*?>)|([\s\S]*?<\/iframe>)/i,
    specail_chars: "*|,\":<>[]{}`\';()@&$#%",
    checkTags: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        if (this.script_regex.test(val)) {
            return_val = velovalidation.error('script');
        } else if (this.style_regex.test(val)) {
            return_val = velovalidation.error('style');
        } else if (this.iframe_regex.test(val)) {
            return_val = velovalidation.error('iframe');
        }
        return return_val;
    },
    checkFirstname: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 32;
        var minchar = typeof minchar !== 'undefined' ? minchar : 1;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_fname').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_fname').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkMidname: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 32;
        var minchar = typeof minchar !== 'undefined' ? minchar : 1;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_mname').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_mname').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkLastname: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 32;
        var minchar = typeof minchar !== 'undefined' ? minchar : 1;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_lname').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_lname').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkPassword: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 40;
        var minchar = typeof minchar !== 'undefined' ? minchar : 6;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_pass').replace(/#/g, minchar);
                } else {

                    if (!val.match(".*[A-Z].*"))
                        return velovalidation.error('capital_alphabets_pass');

                    if (!val.match(".*[a-z].*"))
                        return velovalidation.error('small_alphabets_pass');

                    if (!val.match(".*\\d.*"))
                        return velovalidation.error('digit_pass');

                    var splChars = "*|,\":<>[]{}`\';()@&$#%";
                    var splChars_exist = false;
                    for (var i = 0; i < val.length; i++) {
                        if (splChars.indexOf(val.charAt(i)) != -1) {
                            splChars_exist = true;
                            break;
                        }
                    }
                    if (!splChars_exist) {
                        return velovalidation.error('specialchar_pass');
                    }
                }
            } else {
                return_val = velovalidation.error('maxchar_pass').replace(/#/g, maxchar);
            }

        }
        return return_val;
    },
    checkMandatory: function (ele, maxchar, minchar) {
        var val = ele.val().trim();
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 255;
        var minchar = typeof minchar !== 'undefined' ? minchar : 0;
        var return_val = true;
        if (val == '') {
            return_val = velovalidation.error('empty_field');
        } else if (val.length > maxchar) {
            return_val = velovalidation.error('maxchar_field').replace(/#/g, maxchar);
        } else if (val.length < minchar) {
            return_val = velovalidation.error('minchar_field').replace(/#/g, minchar);
        } else {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
        }
        return return_val;
    },
    checkMandatoryOnly: function (ele) {
       var val = ele.val().trim();
       var return_val = true;
       if (val == '') {
           return_val = velovalidation.error('empty_field');
       }
       return return_val;
    },
    checkAmount: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        if (val != '') {
            if (!val.match(/^-?\d*(\.\d+)?$/)) {
                return_val = velovalidation.error('valid_amount');
            } else if (!val.match(/^-?\d*(\.\d{1,2})?$/)){
                return_val = velovalidation.error('valid_decimal');
            } else if (val < 0) {
                return_val = velovalidation.error('positive_amount');
            }
        }
        return return_val;
    },
    checkPercentage: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        if (val != '') {
            if (!val.match(/^-?\d*(\.\d+)?$/)) {
                return_val = velovalidation.error('valid_percentage');
            } else if (val < 0 || val > 100) {
                return_val = velovalidation.error('between_percentage');
            }
        }
        return return_val;
    },
    isNumeric: function (ele, positive) {
       var val = ele.val().trim();
       positive = typeof positive !== 'undefined' ? positive : true;
       var return_val = true;
           if (!(/^-?\d*?\d+$/.test(val))) {
               return_val = velovalidation.error('number_field');
           }
           if (positive && return_val == true) {
               if (val < 0) {
                   return_val = velovalidation.error('number_pos');
               }
           }
       return return_val;
   },
    isBetween: function (ele, min, max) {
        var val = ele.val().trim();
        var return_val = true;
        if (val != '') {
            if (val >= min && val <= max) {
                return return_val;
            } else {
                return_val = velovalidation.error('validate_range').replace(/#/g, min).replace(/%/g, max);
            }
        }
        return return_val;
    },
    checkEmail: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 96;
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (val != '') {
            if (val.length > maxchar) {
                return_val = velovalidation.error('max_email').replace(/#/g, maxchar);
            } else if (!regex.test(val)) {
                return_val = velovalidation.error('validate_email');
            }
        }
        return return_val;
    },
    checkCountry: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 64;
        var minchar = typeof minchar !== 'undefined' ? minchar : 3;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_country').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_country').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkState: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 64;
        var minchar = typeof minchar !== 'undefined' ? minchar : 3;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_state').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_state').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkCity: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 64;
        var minchar = typeof minchar !== 'undefined' ? minchar : 3;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_city').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_city').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkProductName: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 255;
        var minchar = typeof minchar !== 'undefined' ? minchar : 3;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_proname').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_proname').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkCategoryName: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 255;
        var minchar = typeof minchar !== 'undefined' ? minchar : 3;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_catname').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_catname').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkZip: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 10;
        var minchar = typeof minchar !== 'undefined' ? minchar : 4;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_zip').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_zip').replace(/#/g, maxchar);
            }
            var splChars = "*|,\":<>[]{}`\';()@&$#%";
            for (var i = 0; i < val.length; i++) {
                if (splChars.indexOf(val.charAt(i)) != -1) {
                    return_val = velovalidation.error('specialchar_zip');
                    break;
                }
            }
        }
        return return_val;
    },
        checkUsername: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 100;
        var minchar = typeof minchar !== 'undefined' ? minchar : 3;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_username').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_username').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkDateddmmyy: function (ele) {
        var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
        var return_val = true;
        var val = ele.val().trim();
        if (val != '') {
            if (val.match(dateformat))
            {
                var opera1 = val.split('/');
                var opera2 = val.split('-');
                lopera1 = opera1.length;
                lopera2 = opera2.length;
                if (lopera1 > 1)
                {
                    var pdate = val.split('/');
                }
                else if (lopera2 > 1)
                {
                    var pdate = val.split('-');
                }
                var dd = parseInt(pdate[0]);
                var mm = parseInt(pdate[1]);
                var yy = parseInt(pdate[2]);
                var ListofDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                if (mm == 1 || mm > 2)
                {
                    if (dd > ListofDays[mm - 1])
                    {
                        return_val = velovalidation.error('invalid_date');
                    }
                }
                if (mm == 2)
                {
                    var lyear = false;
                    if ((!(yy % 4) && yy % 100) || !(yy % 400))
                    {
                        lyear = true;
                    }
                    if ((lyear == false) && (dd >= 29))
                    {
                        return_val = velovalidation.error('invalid_date');
                    }
                    if ((lyear == true) && (dd > 29))
                    {
                        return_val = velovalidation.error('invalid_date');
                    }
                }
            }
            else
            {
                return_val = velovalidation.error('invalid_date');
            }
        } else {
            return_val = velovalidation.error('invalid_date');
        }
        return return_val;
    },
    checkDatemmddyy: function (ele) {
        var dateformat = /^(0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
        var return_val = true;
        var val = ele.val().trim();
        if (val != '') {
            if (val.match(dateformat)) {
                var opera1 = val.split('/');
                var opera2 = val.split('-');
                lopera1 = opera1.length;
                lopera2 = opera2.length;
                // Extract the string into month, date and year  
                if (lopera1 > 1)
                {
                    var pdate = val.split('/');
                }
                else if (lopera2 > 1)
                {
                    var pdate = val.split('-');
                }
                var mm = parseInt(pdate[0]);
                var dd = parseInt(pdate[1]);
                var yy = parseInt(pdate[2]);
                // Create list of days of a month [assume there is no leap year by default]  
                var ListofDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                if (mm == 1 || mm > 2)
                {
                    if (dd > ListofDays[mm - 1])
                    {
                        return_val = velovalidation.error('invalid_date');
                    }
                }
                if (mm == 2)
                {
                    var lyear = false;
                    if ((!(yy % 4) && yy % 100) || !(yy % 400))
                    {
                        lyear = true;
                    }
                    if ((lyear == false) && (dd >= 29))
                    {
                        return_val = velovalidation.error('invalid_date');
                    }
                    if ((lyear == true) && (dd > 29))
                    {
                        return_val = velovalidation.error('invalid_date');
                    }
                }
            }
            else
            {
                return_val = velovalidation.error('invalid_date');
            }
        } else {
            return_val = velovalidation.error('invalid_date');
        }
        return return_val;
    },
    checkSKU: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 64;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                var splChars = "*|,\":<>[]{}`\';()@&$#%";
                for (var i = 0; i < val.length; i++) {
                    if (splChars.indexOf(val.charAt(i)) != -1) {
                        return_val = velovalidation.error('specialchar_sku');
                        break;
                    }
                }
            } else {
                return_val = velovalidation.error('maxchar_sku').replace(/#/g, maxchar);
            }
        }

        return return_val;
    },
    checkPhoneNumber: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 32;
        var regex = /^[0-9()-]+$/;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (!regex.test(val)) {
                    return_val = velovalidation.error('invalid_phone');
                }
            } else {
                return_val = velovalidation.error('maxchar_phone').replace(/#/g, maxchar);
            }
        }

        return return_val;
    },
    checkAddress: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 128;
        var minchar = typeof minchar !== 'undefined' ? minchar : 1;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (val.length < minchar) {
                    return_val = velovalidation.error('minchar_address').replace(/#/g, minchar);
                }
            } else {
                return_val = velovalidation.error('maxchar_address').replace(/#/g, maxchar);
            }
        }

        return return_val;
    },
    checkCompany: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 32;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
            } else {
                return_val = velovalidation.error('maxchar_company').replace(/#/g, maxchar);
            }
        }

        return return_val;
    },
    checkBrandName: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 64;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
            } else {
                return_val = velovalidation.error('maxchar_brand').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkCarrierName: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 255;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
            } else {
                return_val = velovalidation.error('maxchar_shipment').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkIP: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var testip = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        if (val != '') {
            if (!val.match(testip)) {
                return_val = velovalidation.error('invalid_ip');
            }
        }
        return return_val;
    },
    checkUrl: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var myRegExp = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 255;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                if (!myRegExp.test(val)) {
                    return_val = velovalidation.error('invalid_url');
                }
            } else {
                return_val = velovalidation.error('max_url').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkSize: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 10;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                var splChars = "*|,\":<>[]{}`\';()@&$#%";
                for (var i = 0; i < val.length; i++) {
                    if (splChars.indexOf(val.charAt(i)) != -1) {
                        return_val = velovalidation.error('specialchar_size');
                        break;
                    }
                }
            } else {
                return_val = velovalidation.error('maxchar_size').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkUPC: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 12;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                var splChars = "*|,\":<>[]{}`\';()@&$#%";
                for (var i = 0; i < val.length; i++) {
                    if (splChars.indexOf(val.charAt(i)) != -1) {
                        return_val = velovalidation.error('specialchar_upc');
                        break;
                    }
                }
            } else {
                return_val = velovalidation.error('maxchar_upc').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkEAN: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 14;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                var splChars = "*|,\":<>[]{}`\';()@&$#%";
                for (var i = 0; i < val.length; i++) {
                    if (splChars.indexOf(val.charAt(i)) != -1) {
                        return_val = velovalidation.error('specialchar_ean');
                        break;
                    }
                }
            } else {
                return_val = velovalidation.error('maxchar_ean').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    checkBarcode: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var maxchar = typeof maxchar !== 'undefined' ? maxchar : 255;
        if (val != '') {
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            if (val.length <= maxchar) {
                var splChars = "*|,\":<>[]{}`\';()@&$#%";
                for (var i = 0; i < val.length; i++) {
                    if (splChars.indexOf(val.charAt(i)) != -1) {
                        return_val = velovalidation.error('specialchar_bar');
                        break;
                    }
                }
            } else {
                return_val = velovalidation.error('maxchar_bar').replace(/#/g, maxchar);
            }
        }
        return return_val;
    },
    isColor: function (ele) {
        var val = ele.val().trim();
        var firstchar = val.charAt(0);
        var return_val = true;
        if (val != '') {
            val = val.substr(1);
            var maxchar = typeof maxchar !== 'undefined' ? maxchar : 7;
            if(firstchar != '#'){
                return velovalidation.error('invalid_color');
            }
            var tag_check = velovalidation.checkTags(ele);
            if (tag_check != true) {
                return tag_check;
            }
            var myRegExp = /(^[0-9A-F]{6}$)|(^[0-9A-F]{3}$)/i;
            if (!myRegExp.test(val)) {
                return_val = velovalidation.error('invalid_color');
            }
        }
        return return_val;
    },
    isSpecialChar: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        var splChars = "*|,\":<>[]{}`\';()@&$#%";
        for (var i = 0; i < val.length; i++) {
            if (splChars.indexOf(val.charAt(i)) != -1) {
                return_val = velovalidation.error('specialchar');
                break;
            }
        }
        return return_val;
    },
    checkImage: function (ele, setsize, checkby) {
        var val = ele.val().trim();
        var return_val = true;
        /* default size 2 MB */
        setsize = typeof setsize !== 'undefined' ? setsize : 2097152;
        checkby = typeof checkby !== 'undefined' ? checkby : 'kb';
        var show = '';
        var maxval = 0;
        switch (checkby) {
            case 'b':
                show = 'bytes';
                maxval = setsize;
                break;
            case 'kb':
                show = 'KB';
                maxval = setsize / 1024;
                break;

            case 'mb':
                show = 'MB';
                maxval = setsize / (1024 * 1024);
                break;
            default:
                show = 'KB';
                maxval = setsize / 1024;
        }

        if (ele.prop("files")[0].size > setsize) {
            return_val = velovalidation.error('image_size').replace(/#/g, maxval + ' ' + show);
        }
        var Extension = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
        if (Extension == "jpeg" || Extension == "JPEG" || Extension == "png" || Extension == "jpg" || Extension == "gif") {
        } else {
            return_val = velovalidation.error('not_image');
        }
        return return_val;
    },
    checkAllIP: function (ele, separator) {
        var val = ele.val().trim();
        var return_val = true;
        if (val != '') {
            separator = typeof separator !== 'undefined' ? separator : ',';
            var result = val.split(separator);
            var error = false;
            $.each(result, function (key, value) {
                var testip = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
                if (!value.match(testip)) {
                    error = true;
                }
            });
            if (error) {
                return_val = velovalidation.error('invalid_ip');
            }
        }
        return return_val;
    },
    checkCommaSeparateValue: function(ele,separator){
        var val = ele.val().trim();
        var return_val = true;
        if (val != '') {
            separator = typeof separator !== 'undefined' ? separator : ',';
            var result = val.split(separator);
            var error = false;
            $.each(result, function (key, value) {
                if (value == "") {
                    error = true;
                }
            });
            if (error) {
                return_val = velovalidation.error('invalid_separator').replace(/#/g,separator);
            }
        }
        return return_val;
    },
    checkHtmlTags: function (ele) {
        var val = ele.val().trim();
        var return_val = true;
        if (val != '') {
            if(val.match(/([\<])([^\>]{1,})*([\>])/i)){
                return_val = velovalidation.error('html_tags');
            }
        }
        return return_val;
    },
    error: function (key) {
        var error_arr = {
            empty_fname: 'Please enter First name.',
            maxchar_fname: 'First name cannot be greater than # characters.',
            minchar_fname: 'First name cannot be less than # characters.',
            empty_mname: 'Please enter middle name.',
            maxchar_mname: 'Middle name cannot be greater than # characters.',
            minchar_mname: 'Middle name cannot be less than # characters.',
            only_alphabet: 'Only alphabets are allowed.',
            empty_lname: 'Please enter Last name.',
            maxchar_lname: 'Last name cannot be greater than # characters.',
            minchar_lname: 'Last name cannot be less than # characters.',
            alphanumeric: 'Field should be alphanumeric.',
            empty_pass: 'Please enter Password.',
            maxchar_pass: 'Password cannot be greater than # characters.',
            minchar_pass: 'Password cannot be less than # characters.',
            specialchar_pass: 'Password should contain atleast 1 special character.',
            alphabets_pass: 'Password should contain alphabets.',
            capital_alphabets_pass: 'Password should contain atleast 1 capital letter.',
            small_alphabets_pass: 'Password should contain atleast 1 small letter.',
            digit_pass: 'Password should contain atleast 1 digit.',
            empty_field: 'Field cannot be empty.',
            number_field: 'You can enter only numbers.',            
            positive_number: 'Number should be greater than 0.',
            maxchar_field: 'Field cannot be greater than # characters.',
            minchar_field: 'Field cannot be less than # character(s).',
            empty_email: 'Please enter Email.',
            validate_email: 'Please enter a valid Email.',
            empty_country: 'Please enter country name.',
            maxchar_country: 'Country cannot be greater than # characters.',
            minchar_country: 'Country cannot be less than # characters.',
            empty_city: 'Please enter city name.',
            maxchar_city: 'City cannot be greater than # characters.',
            minchar_city: 'City cannot be less than # characters.',
            empty_state: 'Please enter state name.',
            maxchar_state: 'State cannot be greater than # characters.',
            minchar_state: 'State cannot be less than # characters.',
            empty_proname: 'Please enter product name.',
            maxchar_proname: 'Product cannot be greater than # characters.',
            minchar_proname: 'Product cannot be less than # characters.',
            empty_catname: 'Please enter category name.',
            maxchar_catname: 'Category cannot be greater than # characters.',
            minchar_catname: 'Category cannot be less than # characters.',
            empty_zip: 'Please enter zip code.',
            maxchar_zip: 'Zip cannot be greater than # characters.',
            minchar_zip: 'Zip cannot be less than # characters.',
            empty_username: 'Please enter Username.',
            maxchar_username: 'Username cannot be greater than # characters.',
            minchar_username: 'Username cannot be less than # characters.',
            invalid_date: 'Invalid date format.',
            maxchar_sku: 'SKU cannot be greater than # characters.',
            minchar_sku: 'SKU cannot be less than # characters.',
            invalid_sku: 'Invalid SKU format.',
            empty_sku: 'Please enter SKU.',
            validate_range: 'Number is not in the valid range. It should be betwen # and ##',
            empty_address: 'Please enter address.',
            minchar_address: 'Address cannot be less than # characters.',
            maxchar_address: 'Address cannot be greater than # characters.',
            empty_company: 'Please enter company name.',
            minchar_company: 'Company name cannot be less than # characters.',
            maxchar_company: 'Company name cannot be greater than # characters.',
            invalid_phone: 'Phone number is invalid.',
            empty_phone: 'Please enter phone number.',
            minchar_phone: 'Phone number cannot be less than # characters.',
            maxchar_phone: 'Phone number cannot be greater than # characters.',
            empty_brand: 'Please enter brand name.',
            maxchar_brand: 'Brand name cannot be greater than # characters.',
            minchar_brand: 'Brand name cannot be less than # characters.',
            empty_shipment: 'Please enter Shimpment.',
            maxchar_shipment: 'Shipment cannot be greater than # characters.',
            minchar_shipment: 'Shipment cannot be less than # characters.',
            invalid_ip: 'Invalid IP format.',
            invalid_url: 'Invalid URL format.',
            empty_url: 'Please enter URL.',
            valid_amount: 'Field should be numeric.',
            valid_decimal: 'Field can have only upto two decimal values.',
            max_email: 'Email cannot be greater than # characters.',
            specialchar_zip: 'Zip should not have special characters.',
            specialchar_sku: 'SKU should not have special characters.',
            max_url: 'URL cannot be greater than # characters.',
            valid_percentage: 'Percentage should be in number.',
            between_percentage: 'Percentage should be between 0 and 100.',
            maxchar_size: 'Size cannot be greater than # characters.',
            specialchar_size: 'Size should not have special characters.',
            specialchar_upc: 'UPC should not have special characters.',
            maxchar_upc: 'UPC cannot be greater than # characters.',
            specialchar_ean: 'EAN should not have special characters.',
            maxchar_ean: 'EAN cannot be greater than # characters.',
            specialchar_bar: 'Barcode should not have special characters.',
            maxchar_bar: 'Barcode cannot be greater than # characters.',
            positive_amount: 'Field should be positive.',
            maxchar_color: 'Color could not be greater than # characters.',
            invalid_color: 'Color is not valid.',
            specialchar: 'Special characters are not allowed.',
            script: 'Script tags are not allowed.',
            style: 'Style tags are not allowed.',
            iframe: 'Iframe tags are not allowed.',
            not_image: 'Uploaded file is not an image',
            image_size: 'Uploaded file size must be less than #.',
            html_tags: 'Field should not contain HTML tags.',
            number_pos: 'You can enter only positive numbers.',
            invalid_separator:'Invalid comma (#) separated values.'
        };
        if (typeof this.language[key] === "undefined") {
            return error_arr[key];
        } else {
            return this.language[key];
        }
    },
    setErrorLanguage: function (language_object) {
        if (typeof language_object === "object") {
            this.language = (language_object);
        } else {
            throw "Object was not passed in function setErrorLanguage()";
        }
    }

}