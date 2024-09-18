@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-xxl-6">
        <h5 class="mb-3">Grade & Subject</h5>
        <div class="card">
            <div class="card-body">
                <p class="text-muted">Select <code>Grade/Class</code> to see respective content.</p>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
                            @foreach ($classes as $index => $item)
                                <a class="nav-link {{ $loop->first ? 'active show' : '' }}" 
                                   id="custom-v-pills-{{ $item->id }}-tab" 
                                   data-bs-toggle="pill" 
                                   href="#custom-v-pills-{{ $item->id }}" 
                                   role="tab" 
                                   aria-controls="custom-v-pills-{{ $item->id }}" 
                                   aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    <i class="ri-home-4-line d-block fs-20 mb-1"></i>
                                    {{ $item->grade }}
                                </a>
                            @endforeach
                        </div>
                    </div> <!-- end col-->
                    <div class="col-lg-9">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            @foreach ($classes as $index => $item)
                                <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" 
                                     id="custom-v-pills-{{ $item->id }}" 
                                     role="tabpanel" 
                                     aria-labelledby="custom-v-pills-{{ $item->id }}-tab">
                                    @php
                                        // Array of possible button styles
                                        $buttonStyles = [
                                            'btn-outline-primary',
                                            'btn-outline-success',
                                            'btn-outline-warning',
                                            'btn-outline-info',
                                            'btn-outline-secondary',
                                            // Add more styles as needed
                                        ];
                                    @endphp
                                    
                                    @if ($item->subjects->isNotEmpty())
                                        <div class="subject-buttons">
                                            @foreach ($item->subjects as $subject)
                                                @php
                                                    // Pick a random style from the array
                                                    $randomStyle = $buttonStyles[array_rand($buttonStyles)];
                                                @endphp
                                                <button type="button" class="btn {{ $randomStyle }} waves-effect waves-light mb-2" 
                                                        data-subject-id="{{ $subject->id }}">
                                                    {{ $subject->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <p>No subjects allocated for this class.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div><!-- end card-body -->
        </div><!-- end card-->
    </div><!-- end col -->
    
    
    
    
    {{-- Add Grade and Subject --}}
    <div class="col-xxl-6" id="content-display">
       
    
    </div><!--end col-->
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.subject-buttons button').forEach(button => {
        button.addEventListener('click', function () {
            const subjectId = this.getAttribute('data-subject-id');

            fetch('{{ route('education.getContentsBySubject') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ subject_id: subjectId })
            })
            .then(response => response.json())
            .then(data => {
                const contentDisplay = document.getElementById('content-display');
                contentDisplay.innerHTML = ''; // Clear previous content

                if (data.contents.length > 0) {
                    data.contents.forEach(content => {
                        const contentElement = document.createElement('div');
                        contentElement.classList.add('content-item');
                        contentElement.innerHTML = `
                            <h6>${content.subject.name}</h6>
                            ${content.generated_content} <!-- Already formatted HTML -->
                        `;
                        contentDisplay.appendChild(contentElement);
                    });
                } else {
                    contentDisplay.innerHTML = '<p>No content available for this subject.</p>';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});


    function formatContent(text) {
    return text
        .replace(/^### (.*$)/gim, '<h3>$1</h3>') // Markdown-like headers
        .replace(/^---$/gim, '<hr>')             // Horizontal rules
        .replace(/\n/g, '<br>');                 // Newline to <br>
}
    </script>


@endsection

