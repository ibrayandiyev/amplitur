<script type="application/ld+json">

    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "{{ route('frontend.index') }}",
        "name": "Amp Travels",
        "potentialAction": {
          "@type": "SearchAction",
          "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ route('frontend.index') }}/package/search?q={search_term_string}"
          },
          "query-input": "required name=search_term_string"
        }
      }

</script>

