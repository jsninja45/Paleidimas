@extends('app')

@section('title'){{ 'Free trial' }}@stop

@section('content')

    <div class="container">
        <div class="page-heading">Try our speech to text services for free!</div>
        <div class="content">
            <p>Tempted to try out the speech to text services? Wondering about the quality of speech to text transcriptions? Now you can do that and more with our free SpeechToText trial!</p>
            <p>For a full 3 minutes of any audio record, be it an interview, presentation or a simple chitchat – our team of professional transcribers can convert it into readable text without any grammatical errors. All you have to do is write us at <a href="mailto:info@speechtotextservice.com">info@speechtotextservice.com</a> by sending your record and we will do the rest for you.</p>
            <p>Our company take pride in providing the highest quality <a href="{{ route('services') }}">transcription services</a> with unprecedented dedication to our customers. Each audio file is examined and assigned to the most competent transcriber of the field the recording represents. Our transcribers has wide range of experience (legal, medical, financial – you name it) to carry out the work with deep knowledge of industry-specific terminology, thus guaranteeing transcripts to be 99% accurate.</p>
            <p>Transcriptions are extensively evaluated by professional editors for accuracy and grammatical errors before submitting them back to the customer. Such process let us guarantee the highest efficiency and quality of our work.</p>
            <p>Challenges of changing media environment face us each day and we are willingly accepting them to provide the best possible service to our customers. The ability to cover an extensive array of material speaks through the <a href="{{ route('reviews') }}">feedback of our customers.</a></p>
            <p>You may find that beyond the free trial, <a href="{{ route('pricing') }}">our prices</a> are among the most competent in the industry with the rates that can satisfy any budget.</p>
            <p>With free trial of speech to text service, we build a bridge between reading about the quality to experiencing the quality. So just upload your file and we will do the rest!</p>
        </div>
    </div>
@stop