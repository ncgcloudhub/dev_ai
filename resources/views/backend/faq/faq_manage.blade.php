@extends('admin.layouts.master')
@section('title')
    FAQ
@endsection
@section('content')
@component('admin.components.breadcrumb')
    @slot('li_1')
        Manage
    @endslot
    @slot('title')
        FAQ
    @endslot
@endcomponent

<!-- Accordions Bordered -->
<div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-secondary" id="accordionBordered">
    <!-- Varying Modal Content -->
<div class="hstack gap-2 flex-wrap">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#varyingcontentModal" data-bs-whatever="@mdo">Add FAQ</button>
</div> 

@foreach ($faqs as $index => $item)
    <div class="accordion-item mt-2">
        <h2 class="accordion-header" id="accordionHeader{{$index}}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionCollapse{{$index}}" aria-expanded="false" aria-controls="accordionCollapse{{$index}}">
               {{$item->question}}
            </button>
        </h2>
        <div id="accordionCollapse{{$index}}" class="accordion-collapse collapse" aria-labelledby="accordionHeader{{$index}}" data-bs-parent="#accordionBordered">
            <div class="accordion-body">
                {{$item->answer}}
            </div>
        </div>
    </div>
@endforeach

</div>

<!-- Varying modal content -->
<div class="modal fade" id="varyingcontentModal" tabindex="-1" aria-labelledby="varyingcontentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyingcontentModalLabel">FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="faqForm">
                    @csrf
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Question</label>
                        <input type="text" name="question" class="form-control" id="recipient-name">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Answer</label>
                        <textarea class="form-control" name="answer" id="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addBtn">Add</button>
            </div>
        </div>
    </div>
  </div>

@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        document.getElementById('addBtn').addEventListener('click', function () {
            var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            
            if (!csrfTokenMeta) {
                console.error('CSRF token meta tag not found.');
                return;
            }
    
            var csrfToken = csrfTokenMeta.getAttribute('content');
    
            var formData = new FormData(document.getElementById('faqForm'));
    
            // Send AJAX request
            fetch('/store/faq', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Handle response
                console.log(data);
                
                // Append the new FAQ item to the accordion section
                var faqContainer = document.getElementById('accordionBordered');
                var newFaqItem = `
                    <div class="accordion-item mt-2">
                        <h2 class="accordion-header" id="accordionHeader${data.faq.id}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionCollapse${data.faq.id}" aria-expanded="false" aria-controls="accordionCollapse${data.faq.id}">
                               ${data.faq.question}
                            </button>
                        </h2>
                        <div id="accordionCollapse${data.faq.id}" class="accordion-collapse collapse" aria-labelledby="accordionHeader${data.faq.id}" data-bs-parent="#accordionBordered">
                            <div class="accordion-body">
                                ${data.faq.answer}
                            </div>
                        </div>
                    </div>
                `;
                faqContainer.insertAdjacentHTML('beforeend', newFaqItem);
    
                // Close the modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('varyingcontentModal'));
                modal.hide();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
    
    
    
    
@endsection