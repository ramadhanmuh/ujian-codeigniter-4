/**
 * isSafeUrl(url, base)
 *   - Memvalidasi URL untuk attribute seperti href/src.
 *   - Mengizinkan: http:, https:, mailto:, tel:, relative URLs, data:image/* (base64).
 */
function isSafeUrl(url, base = location.href) {
  if (!url) return false;
  // Trim whitespace
  const s = String(url).trim();

  // Disallow control characters
  if (/[\u0000-\u001F]/.test(s)) return false;

  // Allow relative URLs (start with /, ./, ../, #) quickly:
  if (/^(\/|\.\/|\.\.\/|#)/.test(s)) return true;

  // Allow data images (png/jpg/gif/webp) only
  if (/^data:image\/(png|jpeg|jpg|gif|webp);base64,[A-Za-z0-9+/=]+$/i.test(s)) return true;

  // Try to parse URL; use base so relative become absolute
  try {
    const parsed = new URL(s, base);
    const scheme = parsed.protocol.replace(':', '').toLowerCase();
    return ['http', 'https', 'mailto', 'tel'].includes(scheme);
  } catch (e) {
    return false;
  }
}

/**
 * sanitizeHTML(dirty, options)
 *  - members:
 *      allowedTags: Set or array of allowed tag names (lowercase)
 *      allowedAttributes: object map tag -> Set(attrs) plus '*' for all tags
 *  - returns sanitized HTML string
 */
function sanitizeHTML(dirty, options = {}) {
  if (dirty === null || dirty === undefined) return '';

  const DEFAULT_ALLOWED_TAGS = new Set([
    'a','b','i','u','strong','em','br','p','div','span','ul','ol','li',
    'img','table','thead','tbody','tr','td','th','h1','h2','h3','h4','h5','h6',
    'pre','code','blockquote'
  ]);

  const DEFAULT_ALLOWED_ATTRS = {
    '*': new Set(['title', 'alt', 'aria-label', 'role', 'class', 'id', 'data-*']),
    'a': new Set(['href', 'title', 'target', 'rel']),
    'img': new Set(['src', 'alt', 'title', 'width', 'height']),
    'table': new Set(['border', 'cellpadding', 'cellspacing'])
  };

  const allowedTags = options.allowedTags ? new Set(options.allowedTags.map(t => t.toLowerCase())) : DEFAULT_ALLOWED_TAGS;
  const allowedAttributes = Object.assign({}, DEFAULT_ALLOWED_ATTRS, options.allowedAttributes || {});

  // Helper: check if attribute is allowed for this tag
  function attrAllowed(tagName, attrName) {
    attrName = attrName.toLowerCase();
    const tagRules = allowedAttributes[tagName] || null;
    const globalRules = allowedAttributes['*'] || null;

    const checkSet = (s) => {
      if (!s) return false;
      if (s.has(attrName)) return true;
      // wildcard for data-*
      if (attrName.startsWith('data-') && Array.from(s).some(a => a.endsWith('*') && a.startsWith('data-'))) return true;
      return false;
    };
    return checkSet(tagRules) || checkSet(globalRules);
  }

  // Parse into DOM using template (safe)
  const template = document.createElement('template');
  template.innerHTML = String(dirty);

  // Traverse and sanitize
  function sanitizeNode(node) {
    // Text node: ok
    if (node.nodeType === Node.TEXT_NODE) return;

    // Comment nodes: remove
    if (node.nodeType === Node.COMMENT_NODE) {
      node.remove();
      return;
    }

    if (node.nodeType === Node.ELEMENT_NODE) {
      const tag = node.tagName.toLowerCase();

      // If tag not allowed -> replace by its textContent (safe) to avoid keeping nested malicious attributes
      if (!allowedTags.has(tag)) {
        // Replace node with escaped text node containing the text content
        const txt = document.createTextNode(node.textContent);
        node.replaceWith(txt);
        return; // done for this branch
      }

      // Clean attributes
      // Copy attributes to avoid live mutation problems
      const attrs = Array.from(node.attributes || []);
      for (const attr of attrs) {
        const name = attr.name;
        const value = attr.value;

        // Remove event handlers and javascript: and style attribute
        if (/^on/i.test(name)) {
          node.removeAttribute(name);
          continue;
        }
        if (name.toLowerCase() === 'style') {
          node.removeAttribute(name);
          continue;
        }

        // If attribute not in allowlist for this tag -> remove
        if (!attrAllowed(tag, name)) {
          node.removeAttribute(name);
          continue;
        }

        // If attribute is href or src -> validate value
        if (['href','src','xlink:href'].includes(name.toLowerCase())) {
          if (!isSafeUrl(value)) {
            node.removeAttribute(name);
            continue;
          }
          // Extra protections: for anchors, add rel="noopener noreferrer" if target="_blank"
          if (tag === 'a' && node.getAttribute('target') === '_blank') {
            node.setAttribute('rel', 'noopener noreferrer');
          }
        }

        // If attribute is 'target' ensure only allowed values
        if (name.toLowerCase() === 'target') {
          const v = value.toLowerCase();
          if (!['_self','_blank','_parent','_top'].includes(v)) {
            node.removeAttribute(name);
          }
        }
      }

      // Recurse into children (note: children may change during recursion)
      const children = Array.from(node.childNodes);
      for (const ch of children) sanitizeNode(ch);
    } else {
      // For any other node types, remove
      node.remove();
    }
  }

  const children = Array.from(template.content.childNodes);
  for (const c of children) sanitizeNode(c);

  return template.innerHTML;
}


$(document).ready(function () {
    var listURL = $('#question-list-column').data('url');

    var orderBy = $('#order').find(':selected').data('order');

    var direction = $('#order').find(':selected').data('direction')

    var examId = $('#exam_id').find(':selected').val()

    var createButtonDisabled = true
    
    var keyword = $('#keyword').val()

    var page = 1
    
    function getData() {
        $.ajax({
            url: listURL,
            type: 'GET',
            data: {
                orderBy: orderBy,
                direction: direction,
                exam_id: examId,
                keyword: keyword,
                page: page
            },
            dataType: 'json',
            success: function (response) {                
                $('#loader').addClass('d-none')
                $('#loader').removeClass('d-flex')

                if (response.records.length === 0) {
                    $('#question-list-column').html('<div class="text-center">Data tidak ditemukan.<div>')
                    $('#question-total-column').text('Total : 0')
                } else {
                    var questionHTML = ''

                    $.each(response.records, function (index, value) {
                        questionHTML += '<div class="row mb-3">'
                        questionHTML +=     '<div class="col-auto">'
                        questionHTML +=         value.number + '.'
                        questionHTML +=     '</div>'

                        questionHTML +=     '<div class="col">'
                        questionHTML +=         '<div class="col-12">'
                        questionHTML +=             '<p>' + sanitizeHTML(value.text) + '</p>'
                        questionHTML +=         '</div>'
                        questionHTML +=         '<div class="col-12">'
                        questionHTML +=             '<span>A. ' + sanitizeHTML(value.option_a) + '</span>'

                        if (value.correct_answer === 'a')
                        questionHTML += '<span class="ms-1 badge bg-primary"><i class="bx bx-check"></i></span>'

                        questionHTML +=         '</div>'
                        questionHTML +=         '<div class="col-12">'
                        questionHTML +=             '<span>B. ' + sanitizeHTML(value.option_b) + '</span>'

                        if (value.correct_answer === 'b')
                        questionHTML += '<span class="ms-1 badge bg-primary"><i class="bx bx-check"></i></span>'

                        questionHTML +=         '</div>'
                        questionHTML +=         '<div class="col-12">'
                        questionHTML +=             '<span>C. ' + sanitizeHTML(value.option_c) + '</span>'

                        if (value.correct_answer === 'c')
                        questionHTML += '<span class="ms-1 badge bg-primary"><i class="bx bx-check"></i></span>'

                        questionHTML +=         '</div>'
                        questionHTML +=         '<div class="col-12">'
                        questionHTML +=             '<span>D. ' + sanitizeHTML(value.option_d) + '</span>'

                        if (value.correct_answer === 'd')
                        questionHTML += '<span class="ms-1 badge bg-primary"><i class="bx bx-check"></i></span>'

                        questionHTML +=         '</div>'
                        questionHTML +=     '</div>'

                        if (value.csrf !== undefined) {
                          questionHTML +=     '<div class="col-auto">'
                          questionHTML +=         '<div class="btn-group">'
                          questionHTML +=             '<button'
                          questionHTML +=                     ' type="button"'
                          questionHTML +=                     ' class="btn btn-outline-primary btn-icon rounded-pill dropdown-toggle hide-arrow"'
                          questionHTML +=                     ' data-bs-toggle="dropdown"'
                          questionHTML +=              '>'
                          questionHTML +=                 '<i class="bx bx-dots-vertical-rounded"></i>'
                          questionHTML +=             '</button>'
                          questionHTML +=             '<ul class="dropdown-menu dropdown-menu-end">'
                          questionHTML +=                 '<li>'
                          questionHTML +=                     '<a'
                          questionHTML +=                         ' class="dropdown-item"'
                          questionHTML +=                         ' href="' + value.edit_link + '"'
                          questionHTML +=                     '>'
                          questionHTML +=                         'Ubah'
                          questionHTML +=                     '</a>'
                          questionHTML +=                 '</li>'
                          questionHTML +=                 '<li>'
                          questionHTML +=                     '<form'
                          questionHTML +=                         ' method="POST"'
                          questionHTML +=                         ' action="' + value.delete_link + '"'
                          questionHTML +=                     '>'
                          questionHTML +=                         value.csrf
                          questionHTML +=                         '<button'
                          questionHTML +=                                 ' class="dropdown-item"'
                          questionHTML +=                                 ' type="submit"'
                          questionHTML +=                          '>'
                          questionHTML +=                             'Hapus'
                          questionHTML +=                          '</button>'
                          questionHTML +=                     '</form>'
                          questionHTML +=                 '</li>'
                          questionHTML +=             '</ul>'
                          questionHTML +=         '</div>'
                          questionHTML +=     '</div>'
                        }

                        questionHTML += '</div>'
                    })               

                    $('#question-list-column').html(questionHTML)

                    $('#question-total-column').text('Total : ' + response.total)

                    $('#question-pagination-column').remove()

                    var paginationHTML = ''

                    paginationHTML += '<div class="col-md" id="question-pagination-column">'

                    paginationHTML +=   '<nav>'

                    paginationHTML +=       '<ul class="pagination justify-content-md-end">'

                    $.each(response.pageItems, function (index, value) {

                        var liClass = 'page-item'

                        if (value.secondary !== undefined) {
                            liClass += ' d-none d-md-inline'
                        }

                        if (value.previous !== undefined) {
                            liClass += ' previous'
                        }

                        if (value.first !== undefined) {
                            liClass += ' first'
                        }

                        if (value.next !== undefined) {
                            liClass += ' next'
                        }

                        if (value.last !== undefined) {
                            liClass += ' last'
                        }

                        if (value.active !== undefined) {
                            liClass += ' active'
                        }

                        paginationHTML += '<li class="'+ liClass +'">'

                        paginationHTML +=   '<a href="" class="page-link" data-keyword="' + value.keyword + '" data-orderby="' + value.orderBy + '" data-direction="' + value.direction + '" data-page="' + value.page + '" data-examid="'+ value.examId +'">'

                        paginationHTML +=       value.text

                        paginationHTML +=   '</a>'

                        paginationHTML += '</li>'
                    })

                    paginationHTML +=       '</ul>'

                    paginationHTML +=   '</nav>'

                    paginationHTML += '</div>'

                    $('#question-total-column').after(paginationHTML);
                }

                if (response.hasStarted) {
                  createButtonDisabled = true
                } else {
                  createButtonDisabled = false
                }

                setCreateButton()
            },
            error: function (xhr, status, error) {
                console.log(error)
            }
        })   
    }

    function setCreateButton() {
        $('#createButton').attr(
            'href',
            $('#createButton').data('link') + '?exam_id=' + examId
        )

        if (createButtonDisabled) {
          $('#createButton').addClass('disabled')
        } else {
          $('#createButton').removeClass('disabled')
        }
    }

    getData()

    $('#order').change(function () {
        page = 1

        orderBy = $(this).find(':selected').data('order')
        direction = $(this).find(':selected').data('direction')

        $('#loader').addClass('d-flex')
        $('#loader').removeClass('d-none')

        $('#question-pagination-column').remove()

        getData()
    })

    $('#exam_id').change(function () {
        page = 1
        
        examId = $(this).find(':selected').val()

        $('#loader').addClass('d-flex')
        $('#loader').removeClass('d-none')

        $('#question-pagination-column').remove()

        getData()
    })

    $('#searchForm').submit(function (event) {
      event.preventDefault()

      keyword = $('#keyword').val()

      $('#loader').addClass('d-flex')
      $('#loader').removeClass('d-none')

      $('#question-pagination-column').remove()

      getData()
    })

    $(document).on('click', '.page-link', function (event) {
        event.preventDefault()

        keyword = $(this).data('keyword')
        direction = $(this).data('direction')
        page = $(this).data('page')
        orderBy = $(this).data('orderby')
        examId = $(this).data('examid')

        if (keyword === 'undefined') {
            keyword = ''
        }

        if (direction === 'undefined') {
            direction = ''
        }

        if (page === 'undefined') {
            page = ''
        }

        if (orderBy === 'undefined') {
            orderBy = ''
        }

        if (examId === 'undefined') {
            examId = ''
        }

        $('#loader').addClass('d-flex')
        $('#loader').removeClass('d-none')

        $('#question-pagination-column').remove()

        getData()
    })
})