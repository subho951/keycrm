/**
 *  Form Wizard
 */

'use strict';

(function () {
  const select2 = $('.select2'),
    selectPicker = $('.selectpicker');

  // Wizard Validation
  // --------------------------------------------------------------------
  const wizardValidation = document.querySelector('#wizard-validation');
  if (typeof wizardValidation !== undefined && wizardValidation !== null) {
    // Wizard form
    const wizardValidationForm = wizardValidation.querySelector('#wizard-validation-form');
    // Wizard steps
    const wizardValidationFormStep1 = wizardValidationForm.querySelector('#account-details-validation');
    const wizardValidationFormStep2 = wizardValidationForm.querySelector('#personal-info-validation');
    const wizardValidationFormStep3 = wizardValidationForm.querySelector('#social-links-validation');
    // Wizard next prev button
    const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
    const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));

    const validationStepper = new Stepper(wizardValidation, {
      linear: true
    });

    // Account details
    const FormValidation1 = FormValidation.formValidation(wizardValidationFormStep1, {
      fields: {
        position_name: {
          validators: {
            notEmpty: {
              message: 'Position Name is required'
            }
          }
        },
        employer_id: {
          validators: {
            notEmpty: {
              message: 'Employer is required'
            }
          }
        },
        job_type: {
          validators: {
            notEmpty: {
              message: 'Job Type is required'
            }
          }
        },
        industry: {
          validators: {
            notEmpty: {
              message: 'Industry is required'
            }
          }
        },
        job_category: {
          validators: {
            notEmpty: {
              message: 'Job Category is required'
            }
          }
        },
        nationality: {
          validators: {
            notEmpty: {
              message: 'Nationality is required'
            }
          }
        },
        gender: {
          validators: {
            notEmpty: {
              message: 'Gender is required'
            }
          }
        },
        open_position_number: {
          validators: {
            notEmpty: {
              message: 'Number of open positions is required'
            }
          }
        },
        contract_type: {
          validators: {
            notEmpty: {
              message: 'Contract Type is required'
            }
          }
        },
        designation: {
          validators: {
            notEmpty: {
              message: 'Designation is required'
            }
          }
        },
        functional_area: {
          validators: {
            notEmpty: {
              message: 'Functional Area is required'
            }
          }
        },
        min_exp_year: {
          validators: {
            notEmpty: {
              message: 'Min Exp is required'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6, .col-sm-12'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          //* Move the error message out of the `input-group` element
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      validationStepper.next();
    });

    // Personal info
    const FormValidation2 = FormValidation.formValidation(wizardValidationFormStep2, {
      fields: {
        // formValidationFirstName: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The first name is required'
        //     }
        //   }
        // },
        // formValidationLastName: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The last name is required'
        //     }
        //   }
        // },
        // formValidationCountry: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The Country is required'
        //     }
        //   }
        // },
        // formValidationLanguage: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The Languages is required'
        //     }
        //   }
        // }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      validationStepper.next();
    });

    // Bootstrap Select (i.e Language select)
    if (selectPicker.length) {
      selectPicker.each(function () {
        var $this = $(this);
        $this.selectpicker().on('change', function () {
          FormValidation2.revalidateField('formValidationLanguage');
        });
      });
    }

    // select2
    if (select2.length) {
      select2.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this
          .select2({
            placeholder: 'Select an country',
            dropdownParent: $this.parent()
          })
          .on('change', function () {
            // Revalidate the color field when an option is chosen
            FormValidation2.revalidateField('formValidationCountry');
          });
      });
    }

    // Social links
    const FormValidation3 = FormValidation.formValidation(wizardValidationFormStep3, {
      fields: {
        // formValidationTwitter: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The Twitter URL is required'
        //     },
        //     uri: {
        //       message: 'The URL is not proper'
        //     }
        //   }
        // },
        // formValidationFacebook: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The Facebook URL is required'
        //     },
        //     uri: {
        //       message: 'The URL is not proper'
        //     }
        //   }
        // },
        // formValidationGoogle: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The Google URL is required'
        //     },
        //     uri: {
        //       message: 'The URL is not proper'
        //     }
        //   }
        // },
        // formValidationLinkedIn: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The LinkedIn URL is required'
        //     },
        //     uri: {
        //       message: 'The URL is not proper'
        //     }
        //   }
        // }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // You can submit the form
      wizardValidationForm.submit()
      // or send the form data to server via an Ajax request
      // To make the demo simple, I just placed an alert
      // alert('Submitted..!!');
      // this.form.submit();
    });

    wizardValidationNext.forEach(item => {
      item.addEventListener('click', event => {
        // When click the Next button, we will validate the current step
        switch (validationStepper._currentIndex) {
          case 0:
            FormValidation1.validate();
            break;

          case 1:
            FormValidation2.validate();
            break;

          case 2:
            FormValidation3.validate();
            break;

          default:
            break;
        }
      });
    });

    wizardValidationPrev.forEach(item => {
      item.addEventListener('click', event => {
        switch (validationStepper._currentIndex) {
          case 2:
            validationStepper.previous();
            break;

          case 1:
            validationStepper.previous();
            break;

          case 0:

          default:
            break;
        }
      });
    });
  }
})();
