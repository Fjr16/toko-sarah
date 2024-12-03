// Useful component functions

// Return an object that contains references to DOM objects.
function getRefs(el) {
    let result = {};
    
    [...el.querySelectorAll('[data-ref]')]
      .forEach(ref => {
        result[ref.dataset.ref] = ref;
      });
    
    return result;
  }
  
  // Set non-existant object properties to default values.
  function setDefaults(obj, defaults) {
    let results = obj;
    
    for (const prop in defaults) {
      if (!obj.hasOwnProperty(prop)) {
        results[prop] = defaults[prop];
      }
    }
    
    return results;
  }
  
  // Get initial component data from the `data-props` attribute in JSON format.
  function getProps(el, defaults={}) {
    return setDefaults(
      JSON.parse(el.dataset.props ?? '{}'),
      defaults
    );
  }
  
  // Create a new element from an HTML string.
  function createFromHTML(html='') {
    let element = document.createElement(null);
    element.innerHTML = html;
    return element.firstElementChild;
  }

  function repeaterComponent($el) {
    const $props = getProps($el, { maxRows: 5 });
    const $refs = getRefs($el);
    
    let rowHTML = $refs.rows.children[0].outerHTML;
    
    // Hook up events for the row.
    function setUpRow(row) {
      const rowRefs = getRefs(row);
      
      rowRefs.removeButton.onclick = (e) => {
        e.preventDefault();
        removeRow(row);
      };
    }
    
    // Enable or disable addButton as necessary.
    function updateAddButton() {
      if ($refs.rows.children.length >= $props.maxRows) {
        $refs.addButton.setAttribute('disabled', '');
        return;
      }
      
      $refs.addButton.removeAttribute('disabled');
    }
    
    // Update array key values to the row number
    function updateFieldNames() {
      [...$refs.rows.children]
        .forEach((el, index) => {
          el.querySelectorAll('[name]')
            .forEach(el => {
              const newName = el.getAttribute('name').replace(/\[\d\]/gm, `[${index}]`);
            
              el.setAttribute('name', newName);
            });
        });
    }
    
    function addRow() {
      if (
        !rowHTML ||
        $refs.rows.children.length >= $props.maxRows
      ) return;
      
      let newRow = createFromHTML(rowHTML);
      newRow.removeAttribute('id');
      setUpRow(newRow);
      
      $refs.rows.appendChild(newRow);
      newRow.querySelector('input,textarea,select').focus();
      
      updateFieldNames();
      updateAddButton();
    }
    
    function removeRow(row) {
      if ($refs.rows.children.length <= 1) return;
      
      row.remove();
      $refs.rows.focus();
      
      updateFieldNames();
      updateAddButton();
    }
    
    function init() {
      setUpRow($refs.rows.children[0]);
      
      $refs.addButton.onclick = (e) => {
        e.preventDefault();
        addRow();
      }
      
      updateFieldNames();
    }
    
    init();
  }

  

  
  
  document.querySelectorAll('[data-component="repeater"]')
    .forEach(el => {
      repeaterComponent(el);
    });