import json

# Path to the JSON file
json_file = 'BAPgrade.json'

# Read data from the JSON file
def read_json():
    try:
        with open(json_file, 'r', encoding='utf-8') as f:
            data = json.load(f)
        print('Data loaded from JSON:')
        print(data)
        return data
    except FileNotFoundError:
        print('JSON file not found. Returning empty dict.')
        return {}
    except json.JSONDecodeError:
        print('JSON decode error. Returning empty dict.')
        return {}

# Write data to the JSON file
def write_json(data):
    with open(json_file, 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=4)
    print('Data written to JSON.')

# Example usage
def main():
    # Read existing data
    data = read_json()
    # Modify data (example: add a new grade)
    data['grades'] = data.get('grades', [])
    data['grades'].append({'student_id': 1, 'grade': 95})
    # Write updated data
    write_json(data)

if __name__ == '__main__':
    main()
