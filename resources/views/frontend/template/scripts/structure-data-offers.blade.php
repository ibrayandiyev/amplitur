<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "{{ $package->name }}",
      "startDate": "{{ $package->starts_at->format('Y-m-d') }}",
      "endDate": "{{ $package->ends_at->format('Y-m-d') }}",
      "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
      "eventStatus": "https://schema.org/EventScheduled",
      "location": {
        "@type": "Place",
        "name": "{{ $package->location }}",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "{{ $package->address->address }}, {{ $package->address->number }}",
          "addressLocality": "{{ $package->getCity() }}",
          "postalCode": "",
          "addressRegion": "{{ $package->address->state }}",
          "addressCountry": "{{ $package->address->country }}"
        }
      },
      "image": [
        "{{ $package->getThumbnailUrl() }}"
       ],
      "description": "{{ $package->meta_description }}",
      "offers": {
        "@type": "Offer",
        "url": "{{ $package->geturl() }}",
        "price": "{{ decimal($package->getLowerPrice()) }}",
        "priceCurrency": "{{ currency()->code }}",
        "availability": "https://schema.org/InStock",
        "validFrom": "{{ $package->created_at->format('Y-m-d') }}"
      },
      "performer": {
        "@type": "PerformingGroup",
        "name": "SHOW"
      },
      "organizer": {
        "@type": "Organization",
        "name": "Amp Travels",
        "url": "https:www.amplitur.com"
      }
    }
    </script>
